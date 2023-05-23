<?php

namespace App\Helpers\Scrapers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\Document;
use App\Models\Disk;
use App\Helpers\Settings;
use App\Models\Proxy;

class Academia
{
    private $baseURL = 'https://www.academia.edu/v0/search/integrated_search';

    public function __construct()
    {
        //
    }

    public function download($url, $fileType, $thumbnail = false)
    {
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
            ];
        }
        // [END] Get storage

        $fileUniqueName = Str::random(64);
        $fileName = 'documents/' . $fileUniqueName . '.' . $fileType;
        Storage::disk('public')->put($fileName, file_get_contents($url));

        $document = new Document($fileName, $fileType);
        $documentText = $document->getText();
        if ($documentText) {
            $disk->put($fileName, Storage::disk('public')->get($fileName));
            Storage::disk('public')->delete($fileName);

            $disk->put('text/' . $fileUniqueName . '.txt', $documentText);

            if ($thumbnail) {
                try {
                    $disk->put('thumbnails/' . $fileUniqueName . '.webp', file_get_contents($thumbnail));
                    $thumbnail = $disk->url('thumbnails/' . $fileUniqueName . '.webp');
                    $thumbnailPath = 'thumbnails/' . $fileUniqueName . '.webp';
                }
                catch (\ErrorException $error) {
                    $thumbnail = null;
                    $thumbnailPath = null;
                }
            }
            else {
                $thumbnail = null;
                $thumbnailPath = null;
            }

            $result = [
                'file_url' => $disk->url('documents/' . $fileUniqueName . '.' . $fileType),
                'file_path' => 'documents/' . $fileUniqueName . '.' . $fileType,
                'file_size' => $this->getFileSize($disk->size('documents/' . $fileUniqueName . '.' . $fileType)),
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
            Storage::disk('public')->delete($fileName);
            return [
                'success' => false,
                'description' => 'Tidak dapat mengekstrak dokumen',
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

    public function getDocuments($keyword)
    {
        $settings = new Settings('proxy');
        $useProxy = $settings->get('use_proxy') == 'Y';

        $offset = 0;
        $pageSize = 10;

        $params = [];
        $params['camelize_keys'] = 'true';
        $params['canonical'] = 'true';
        $params['fake_results'] = 'null';
        $params['json'] = 'true';
        $params['last_seen'] = 'null';
        $params['offset'] = $offset;
        $params['query'] = $keyword;
        $params['search_mode'] = 'works';
        $params['size'] = $pageSize;
        $params['sort'] = 'relevance';
        $params['subdomain_param'] = 'api';
        $params['user_language'] = 'en';

        $results = [];
        $loop = true;
        $iteration = 1;
        while ($loop) {
            try {
                if ($offset > 0) {
                    $params['offset'] = $offset;
                }

                if ($useProxy) {
                    $proxy = Proxy::inRandomOrder()->first();
                    $response = Http::retry(3, 100)
                        ->withOptions([
                            'proxy' => 'http://' . $proxy->username . ':' . $proxy->password . '@' . $proxy->ip . ':' . $proxy->port,
                        ])
                        ->get($this->baseURL, $params);
                }
                else {
                    $response = Http::retry(3, 100)
                        ->get($this->baseURL, $params);
                }
            }
            catch (\Illuminate\Http\Client\RequestException $error) {
                return [
                    'success' => false,
                    'description' => 'Error HTTP',
                ];
            }
            
            if ($response->ok()) {
                $responseJSON = json_decode($response->body(), true);
                $works = $responseJSON['works'];
                if (count($works) > 0) {

                    foreach ($works as $work) {

                        if (!empty($work['language'])) {
                            $workType = $work['documentType'];

                            $workData = [];
                            $workData['unique_id'] = $work['id'];
                            $workData['title'] = $work['title'];
                            $workData['type'] = $workType == 'book' ? 'book' : 'paper';
                            $workData['file_type'] = $work['downloadableAttachments'][0]['fileType'];
                            $workData['download_url'] = $work['downloadableAttachments'][0]['bulkDownloadUrl'];
                            $workData['pages'] = $work['pageCount'];
                            $workData['thumbnail'] = $work['downloadableAttachments'][0]['scribdThumbnailUrl'];
                            $workData['language'] = $work['language'];
                            $workData['author'] = $work['owner']['displayName'];
    
                            $results[] = $workData;
                        }

                    } // [END] foreach

                    $offset = $iteration * $pageSize;
                    $iteration++;

                }
                else {
                    // No results
                    return [
                        'success' => true,
                        'results' => $results,
                    ];
                    // [END] No results
                }
            }
            else {
                return [
                    'success' => false,
                    'description' => 'Error ' . $response->status(),
                ];
            }
        } // [END] while
    }
}