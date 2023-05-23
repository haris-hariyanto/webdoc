<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ReCAPTCHA;
use Illuminate\Support\Str;
use App\Helpers\Settings;
use App\Helpers\StructuredData;

class DocumentController extends Controller
{
    public function index($document)
    {
        $settings = new Settings(['website', 'document']);

        $document = Document::with(['disk'])->where($settings->get('website.slug'), $document)->first();
        if (!$document) {
            abort(404);
        }

        $document->increment('views');

        $documentTranscript = file_get_contents($document->text_url);

        $relatedDocumentsTotal = $settings->get('document.related_documents', 15);
        $relatedDocuments = Document::where('id', '>', $document->id)
            ->take($relatedDocumentsTotal)
            ->get();
        if (count($relatedDocuments) < $relatedDocumentsTotal) {
            $relatedDocuments = Document::where('id', '<', $document->id)
            ->take($relatedDocumentsTotal)
            ->get();
        }

        // Set placeholders
        $placeholdersKeys = [
            '[app_name]',
            '[current_url]',
            '[document_title]',
            '[document_file_type]',
            '[document_pages]',
            '[document_file_size]',
        ];
        $placeholdersValues = [
            $settings->getFinal('website.name', config('app.name')),
            route('document', [$document->{$settings->get('website.slug')}]),
            $document->title,
            strtoupper($document->file_type),
            $document->pages,
            \App\Helpers\Text::fileSize($document->file_size),
        ];

        $settings->setPlaceholders($placeholdersKeys, $placeholdersValues);
        // [END] Set placeholders

        // Structured data
        $structuredData = new StructuredData();
        $documentData = [];
        $documentData['name'] = $document['title'];
        $documentData['ratingValue'] = 5;
        $documentData['ratingCount'] = 100;
        $structuredData->book($documentData);
        // [END] Structured data

        return view('main.content.document', compact('document', 'documentTranscript', 'relatedDocuments', 'settings', 'structuredData'));
    }

    public function download($document)
    {
        $settings = new Settings('document');

        $document = Document::where($settings->get('slug'), $document)->first();
        if (!$document) {
            abort(404);
        }
        
        return view('main.content.download', compact('document', 'settings'));
    }

    public function getFile(Request $request, $document)
    {
        $request->validate([
            'g-recaptcha-response' => ['required'],
        ]);

        $settings = new Settings('document');

        $document = Document::with(['disk'])->where($settings->get('slug'), $document)->first();
        if (!$document) {
            abort(404);
        }

        $reCAPTCHA = new ReCAPTCHA($request->{'g-recaptcha-response'});

        if (!$reCAPTCHA->verify()) {
            return redirect()->back()->with('recaptchaInvalid', __('reCAPTCHA is invalid'));
        }

        $document->increment('downloads');
    
        return response()->streamDownload(function () use ($document) {
            $disk = Storage::build([
                'driver' => 's3',
                'key' => $document->disk->access_key,
                'secret' => $document->disk->secret_key,
                'region' => !empty($document->disk->region) ? $document->disk->region : 'us-east-1',
                'bucket' => $document->disk->bucket,
                'endpoint' => $document->disk->endpoint,
                'use_path_style_endpoint' => false,
                'throw' => false,
                'visibility' => 'public',
            ]);
            echo $disk->get($document->file_path);

        }, Str::slug($document->title) . '.' . $document->file_type);
    }
}
