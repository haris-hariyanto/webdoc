<?php

namespace App\Console\Commands\Scrapers;

use Illuminate\Console\Command;
use App\Helpers\Scrapers\Scribd as ScribdHandler;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\Keyword;

class Scribd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:scribd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {
        $limit = $this->ask('Batas keyword untuk scrape', 100);

        for ($i = 1; $i <= $limit; $i++) {
            $keyword = Keyword::where('status', 'READY')
                ->orderBy('priority', 'DESC')
                ->orderBy('id', 'ASC')
                ->first();
            
            if (!$keyword) {
                $this->line('[ * ] Semua keyword sudah discrape!');
                return;
            }

            $keyword->update([
                'status' => 'PROCESS',
            ]);

            $isSuccess = $this->grab($keyword->keyword);

            if ($isSuccess) {
                $keyword->update([
                    'status' => 'DONE',
                ]);
            }
            else {
                $keyword->update([
                    'status' => 'FAILED',
                ]);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function grab($keyword)
    {
        /*
        $keyword = $this->ask('Keyword');
        while (empty($keyword)) {
            $keyword = $this->ask('Keyword');
        }
        */

        $scribdHandler = new ScribdHandler();

        $page = 1;
        $loop = true;
        while ($loop) {
            $this->info('[ * ] Halaman : ' . $page);
            $this->line('--------------------');
            $this->line('[ * ] Mencari dokumen : ' . $keyword);
            $this->line('--------------------');
            $getDocuments = $scribdHandler->getDocuments($keyword, $page);

            if ($getDocuments['success']) {
                if (count($getDocuments['results']) < 1) {
                    $this->info('[ * ] Done');
                    $loop = false;
                }

                $iteration = 1;
                foreach ($getDocuments['results'] as $document) {
                    $this->line('[' . $iteration . ' / ' . count($getDocuments['results']) . ']');
                    $this->line('[ * ] Judul : ' . $document['title']);
                    $this->line('[ * ] ID Scribd : ' . $document['unique_id']);
                    $this->line('[ * ] Tipe : ' . $document['type']);
                    $this->line('[ * ] Mendownload dokumen');

                    $isDocumentExists = Document::where('source', 'scribd')
                        ->where('source_id', $document['unique_id'])
                        ->exists();
                    
                    if ($isDocumentExists) {
                        $iteration++;
                        $this->line('--------------------');
                        continue;
                    }

                    $isDownloaded = $scribdHandler->download($document['unique_id']);
                    
                    if ($isDownloaded['success'] == true) {

                        $isDownloaded = $isDownloaded['results'];

                        $dataToSave = [];
                        $dataToSave['title'] = $document['title'];
                        $dataToSave['slug'] = $this->createUniqueSlug($document['title']);
                        $dataToSave['thumbnail'] = $isDownloaded['thumbnail'];
                        $dataToSave['thumbnail_path'] = $isDownloaded['thumbnail_path'];
                        $dataToSave['author'] = $document['author'];
                        $dataToSave['file_size'] = $isDownloaded['file_size'];
                        $dataToSave['disk_id'] = $isDownloaded['disk_id'];
                        $dataToSave['file_url'] = $isDownloaded['file_url'];
                        $dataToSave['file_path'] = $isDownloaded['file_path'];
                        $dataToSave['text_url'] = $isDownloaded['text_url'];
                        $dataToSave['text_path'] = $isDownloaded['text_path'];
                        $dataToSave['file_type'] = 'pdf';
                        $dataToSave['source'] = 'scribd';
                        $dataToSave['source_id'] = $document['unique_id'];
                        $dataToSave['document_type'] = $document['type'];
                        $dataToSave['pages'] = $document['pages'];
                        Document::create($dataToSave);

                    }
                    else {
                        $this->error('[ * ] Error : ' . $isDownloaded['description']);
                        if ($isDownloaded['stopProcess']) {
                            $this->line('--------------------');
                            return;
                        }
                    }
                
                    $this->line('--------------------');
                    $iteration++;
                } // [END] foreach

                $page++;
            }
            else {
                return false;
                $this->error('[ * ] Grab gagal : ' . $getDocuments['description']);
            }
        }

        return true;
    }

    public function createUniqueSlug($title)
    {
        $baseSlug = Str::slug($title);
        $iteration = 1;
        $slug = $baseSlug;
        $loop = true;
        while (Document::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $iteration;
            $iteration++;
        }
        return $slug;
    }
}
