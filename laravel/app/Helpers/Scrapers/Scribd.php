<?php

namespace App\Helpers\Scrapers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Disk;
use Illuminate\Support\Str;
use App\Helpers\Settings;
use App\Models\Proxy;
use App\Models\Cookie;

class Scribd
{
    public function __construct()
    {
        //
    }

    public function download($fileID)
    {
        $settings = new Settings('proxy');
        $useProxy = $settings->get('use_proxy') == 'Y';

        $cookie = Cookie::where('website', 'scribd')->inRandomOrder()->first();
        if (!$cookie) {
            return [
                'success' => false,
                'description' => 'Tidak ada cookie tersedia',
                'stopProcess' => true,
            ];
        }
        else {
            $cookie = $cookie->cookie;
        }

        if ($useProxy) {
            $proxy = Proxy::inRandomOrder()->first();
            $proxy = 'http://' . $proxy->username . ':' . $proxy->password . '@' . $proxy->ip . ':' . $proxy->port;
        }
        else {
            $proxy = false;
        }

        $url = 'https://www.scribd.com/doc-page/download-receipt-modal-props/' . $fileID;
        
        try {
            if ($useProxy) {
                $response = Http::retry(3, 100)
                    ->withOptions([
                        'proxy' => $proxy,
                    ])
                    ->withHeaders([
                        'Cookie' => $cookie,
                    ])
                    ->timeout(3600)
                    ->get($url);
            }
            else {
                $response = Http::retry(3, 100)
                    ->withHeaders([
                        'Cookie' => $cookie,
                    ])
                    ->timeout(3600)
                    ->get($url);
            }
        }
        catch (\Illuminate\Http\Client\RequestException $error) {
            return [
                'success' => false,
                'description' => 'Error HTTP',
                'stopProcess' => true,
            ];
        }

        if ($response->ok()) {
            $responseJSON = json_decode($response->body(), true);
            
            if (isset($responseJSON['document']['access_key']) && isset($responseJSON['document']) && isset($responseJSON['document']['formats']) && isset($responseJSON['document']['image_url'])) {
                $documentFormats = $responseJSON['document']['formats'];

                $isPDFAvailable = false;
                $isTXTAvailable = false;

                foreach ($documentFormats as $documentFormat) {
                    if ($documentFormat['extension'] == 'pdf') {
                        $isPDFAvailable = true;
                    }
                    if ($documentFormat['extension'] == 'txt') {
                        $isTXTAvailable = true;
                    }
                }

                if ($isPDFAvailable && $isTXTAvailable) {
                    $accessKey = $responseJSON['document']['access_key'];

                    // Get storage
                    $randomDisk = Disk::where('is_active', 'Y')->inRandomOrder()->first();
                    if ($randomDisk) {
                        $disk = Storage::build([
                            'driver' => 's3',
                            'key' => $randomDisk->access_key,
                            'secret' => $randomDisk->secret_key,
                            'region' => !empty($randomDisk->region) ? $randomDisk->region : 'us-east-1',
                            'bucket' => $randomDisk->bucket,
                            'endpoint' => $randomDisk->endpoint,
                            'use_path_style_endpoint' => false,
                            'throw' => false,
                            'visibility' => 'public',
                        ]);
                    }
                    else {
                        return [
                            'success' => false,
                            'description' => 'Tidak ada disk tersedia',
                            'stopProcess' => true,
                        ];
                    }
                    // [END] Get storage

                    $fileUniqueName = Str::random(64);

                    $havePDF = false;
                    $haveTXT = false;

                    // Download PDF
                    $urlPDF = 'https://www.scribd.com/document_downloads/' . $fileID;
                    $params = [
                        'secret_password' => $accessKey,
                        'extension' => 'pdf',
                    ];

                    try {
                        if ($useProxy) {
                            $getPDF = Http::retry(3, 100)
                                ->withOptions([
                                    'proxy' => $proxy,
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie,
                                ])
                                ->timeout(3600)
                                ->get($urlPDF, $params);
                        }
                        else {
                            $getPDF = Http::retry(3, 100)
                                ->withOptions([
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie,
                                ])
                                ->timeout(3600)
                                ->get($urlPDF, $params);
                        }
                        
                        $getPDFLocation = $getPDF->header('Location');
                        $getPDFCookie = $getPDF->header('Set-Cookie');

                        if ($useProxy) {
                            $getPDF = Http::retry(3, 100)
                                ->withOptions([
                                    'proxy' => $proxy,
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie . ';' . $getPDFCookie,
                                ])
                                ->timeout(3600)
                                ->get($getPDFLocation);
                        }
                        else {
                            $getPDF = Http::retry(3, 100)
                                ->withOptions([
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie . ';' . $getPDFCookie,
                                ])
                                ->timeout(3600)
                                ->get($getPDFLocation);
                        }
                    }
                    catch (\Illuminate\Http\Client\RequestException $error) {
                        return [
                            'success' => false,
                            'description' => '[PDF] IP limit atau cookie expired',
                            'stopProcess' => true,
                        ];
                    }

                    $fileType = strtolower($getPDF->header('Content-Type'));

                    if ($fileType == 'application/pdf') {
                        $fileName = 'documents/' . $fileUniqueName . '.pdf';
                        $disk->put($fileName, $getPDF->body());
                        $havePDF = true;
                    }
                    else {
                        return [
                            'success' => false,
                            'description' => 'File PDF tidak dapat didownload (cookie expired)',
                            'stopProcess' => true,
                        ];
                    }
                    // [END] Download PDF

                    // Download TXT
                    $urlTXT = 'https://www.scribd.com/document_downloads/' . $fileID;
                    $params = [
                        'secret_password' => $accessKey,
                        'extension' => 'txt',
                    ];

                    try {
                        if ($useProxy) {
                            $getTXT = Http::retry(3, 100)
                                ->withOptions([
                                    'proxy' => $proxy,
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie,
                                ])
                                ->timeout(3600)
                                ->get($urlTXT, $params);
                        }
                        else {
                            $getTXT = Http::retry(3, 100)
                                ->withOptions([
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie,
                                ])
                                ->timeout(3600)
                                ->get($urlTXT, $params);
                        }
                        
                        $getTXTLocation = $getTXT->header('Location');
                        $getTXTCookie = $getTXT->header('Set-Cookie');

                        if ($useProxy) {
                            $getTXT = Http::retry(3, 100)
                                ->withOptions([
                                    'proxy' => $proxy,
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie . ';' . $getTXTCookie,
                                ])
                                ->timeout(3600)
                                ->get($getTXTLocation);
                        }
                        else {
                            $getTXT = Http::retry(3, 100)
                                ->withOptions([
                                    'allow_redirects' => false,
                                ])
                                ->withHeaders([
                                    'Cookie' => $cookie . ';' . $getTXTCookie,
                                ])
                                ->timeout(3600)
                                ->get($getTXTLocation);
                        }
                    }
                    catch (\Illuminate\Http\Client\RequestException $error) {
                        return [
                            'success' => false,
                            'description' => '[TXT] IP limit atau cookie expired',
                            'stopProcess' => true,
                        ];
                    }

                    $fileType = strtolower($getTXT->header('Content-Type'));

                    if ($fileType == 'text/plain; charset=utf-8') {
                        $disk->put('text/' . $fileUniqueName . '.txt', $getTXT->body());
                        $haveTXT = true;
                    }
                    else {
                        return [
                            'success' => false,
                            'description' => 'File TXT tidak dapat didownload (cookie expired)',
                            'stopProcess' => true,
                        ];
                    }
                    // [END] Download TXT

                    if ($havePDF && $haveTXT) {
                        // Download thumbnail
                        try {
                            $disk->put('thumbnails/' . $fileUniqueName . '.webp', file_get_contents($responseJSON['document']['image_url']));
                            $thumbnail = $disk->url('thumbnails/' . $fileUniqueName . '.webp');
                            $thumbnailPath = 'thumbnails/' . $fileUniqueName . '.webp';
                        }
                        catch (\ErrorException $error) {
                            $thumbnail = null;
                            $thumbnailPath = null;
                        }
                        // [END] Download thumbnail

                        $result = [
                            'file_url' => $disk->url('documents/' . $fileUniqueName . '.pdf'),
                            'file_path' => 'documents/' . $fileUniqueName . '.pdf',
                            'file_size' => $this->getFileSize($disk->size('documents/' . $fileUniqueName . '.pdf')),
                            'text_url' => $disk->url('text/' . $fileUniqueName . '.txt'),
                            'text_path' => 'text/' . $fileUniqueName . '.txt',
                            'thumbnail' => $thumbnail,
                            'thumbnail_path' => $thumbnailPath,
                            'disk_id' => $randomDisk->id,
                        ];

                        return [
                            'success' => true,
                            'results' => $result,
                        ];
                    }
                    else {
                        return [
                            'success' => false,
                            'description' => 'Dokumen tidak dapat didownload',
                            'stopProcess' => false,
                        ];
                    }
                }
                else {
                    return [
                        'success' => false,
                        'description' => 'Dokumen tidak dapat di download atau cookie expired',
                        'stopProcess' => false,
                    ];
                }

            }
            else {
                return [
                    'success' => false,
                    'description' => 'Access Key tidak tersedia atau tidak ada format yang bisa digunakan',
                    'stopProcess' => false,
                ];
            }
        }
        else {
            return [
                'success' => false,
                'description' => 'Error ' . $response->status(),
                'stopProcess' => true,
            ];
        }
    }

    private function getFileSize($bytes)
    {
        if ($bytes >= 1024) {
            $bytes = round($bytes / 1024);
        }
        else {
            $bytes = $bytes;
        }
        return $bytes;
    }

    public function getDocuments($keyword, $page)
    {
        $params = [];
        $params['query'] = $keyword;
        $params['content_type'] = 'documents';

        $results = [];
        $loop = true;
        $iteration = 1;
        while ($loop) {
            $params['page'] = $page;

            try {
                $response = Http::retry(3, 100)
                    ->get('https://www.scribd.com/search/query', $params);
            }
            catch (\Illuminate\Http\Client\RequestException $error) {
                return [
                    'success' => false,
                    'description' => 'Error HTTP',
                ];
            }

            if ($response->ok()) {
                $responseJSON = json_decode($response->body(), true);
                
                if (isset($responseJSON['results']) && isset($responseJSON['results']['documents']) && isset($responseJSON['results']['documents']['content']) && isset($responseJSON['results']['documents']['content']['documents']) && $iteration <= 1) {
                    
                    $documents = $responseJSON['results']['documents']['content']['documents'];
                    
                    foreach ($documents as $document) {
                        $documentData = [];
                        $documentData['unique_id'] = $document['id'];
                        $documentData['title'] = $document['title'];
                        $documentData['type'] = $document['type'] == 'book' ? 'book' : 'paper';
                        $documentData['download_url'] = $document['downloadUrl'];
                        $documentData['pages'] = $document['pageCount'];
                        $documentData['thumbnail'] = $document['image_url'];
                        $documentData['author'] = $document['author'];

                        $results[] = $documentData;
                    } // [END] foreach

                    return [
                        'success' => true,
                        'results' => $results,
                    ];

                }
                else {
                    return [
                        'success' => true,
                        'results' => [],
                    ];
                }

            }
            else {
                return [
                    'success' => false,
                    'description' => 'Error ' . $response->status(),
                ];
            }
        }
    }
}