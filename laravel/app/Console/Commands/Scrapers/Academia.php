<?php

namespace App\Console\Commands\Scrapers;

use Illuminate\Console\Command;
use App\Helpers\Scrapers\Academia as AcademiaHandler;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\Keyword;

class Academia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:academia';

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

        $academiaHandler = new AcademiaHandler();

        $this->line('[ * ] Mencari dokumen : ' . $keyword);
        $this->line('--------------------');
        $getDocuments = $academiaHandler->getDocuments($keyword);
        
        if ($getDocuments['success']) {
            $iteration = 1;
            foreach ($getDocuments['results'] as $document) {
                $this->line('[' . $iteration . ' / ' . count($getDocuments['results']) . ']');
                $this->line('[ * ] Judul : ' . $document['title']);
                $this->line('[ * ] ID Academia : ' . $document['unique_id']);
                $this->line('[ * ] Tipe : ' . $document['type']);
                $this->line('[ * ] Format : ' . $document['file_type']);
                $this->line('[ * ] Mendownload dokumen');

                $isDocumentExists = Document::where('source', 'academia')
                    ->where('source_id', $document['unique_id'])
                    ->exists();

                if (empty($document['pages']) || $isDocumentExists) {
                    $iteration++;
                    $this->line('--------------------');
                    continue;
                }

                $isDownloaded = $academiaHandler->download($document['download_url'], $document['file_type'], $document['thumbnail']);
                
                if ($isDownloaded['success'] == true) {

                    $isDownloaded = $isDownloaded['results'];

                    $isDocumentExists2 = Document::where('title', $document['title'])
                        ->where('file_size', $isDownloaded['file_size'])
                        ->exists();
                    if ($isDocumentExists2) {
                        $iteration++;
                        $this->line('--------------------');
                        continue;
                    }
                    
                    $dataToSave = [];
                    $dataToSave['title'] = trim($document['title']);
                    $dataToSave['slug'] = $this->createUniqueSlug(trim($document['title']));
                    $dataToSave['thumbnail'] = $isDownloaded['thumbnail'];
                    $dataToSave['thumbnail_path'] = $isDownloaded['thumbnail_path'];
                    $dataToSave['author'] = $document['author'];
                    $dataToSave['file_size'] = $isDownloaded['file_size'];
                    $dataToSave['disk_id'] = $isDownloaded['disk_id'];
                    $dataToSave['file_url'] = $isDownloaded['file_url'];
                    $dataToSave['file_path'] = $isDownloaded['file_path'];
                    $dataToSave['text_url'] = $isDownloaded['text_url'];
                    $dataToSave['text_path'] = $isDownloaded['text_path'];
                    $dataToSave['file_type'] = $document['file_type'];
                    $dataToSave['source'] = 'academia';
                    $dataToSave['source_id'] = $document['unique_id'];
                    $dataToSave['document_type'] = $document['type'];
                    $dataToSave['pages'] = $document['pages'];
                    $dataToSave['language'] = $document['language'];
                    Document::create($dataToSave);
                }
                else {
                    $this->error('[ * ] Error : ' . $isDownloaded['description']);
                }

                $this->line('--------------------');
                $iteration++;
            } // [END] foreach

            return true;
        }
        else {
            $this->error('[ * ] Grab gagal : ' . $getDocuments['description']);
            return false;
        }
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
