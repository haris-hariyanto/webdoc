<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Helpers\Settings;

class DocumentsController extends Controller
{
    public function popularDocuments(Request $request)
    {
        $settings = new Settings(['website', 'popular-documents']);

        $documents = Document::select('slug', 'alt_slug_1', 'alt_slug_2', 'alt_slug_3', 'alt_slug_4', 'alt_slug_5', 'title', 'thumbnail', 'pages', 'file_type')
            ->orderBy('views', 'DESC')
            ->simplePaginate($settings->get('popular-documents.total_documents', 12));

        // Set placeholders
        $placeholdersKeys = [
            '[app_name]',
            '[current_url]',
        ];
        $placeholdersValues = [
            $settings->getFinal('website.name', config('app.name')),
            route('popular-documents'),
        ];

        $settings->setPlaceholders($placeholdersKeys, $placeholdersValues);
        // [END] Set placeholders

        return view('main.content.popular-documents', compact('documents', 'settings'));
    }

    public function newDocuments()
    {
        $settings = new Settings(['website', 'new-documents']);

        $documents = Document::select('slug', 'alt_slug_1', 'alt_slug_2', 'alt_slug_3', 'alt_slug_4', 'alt_slug_5', 'title', 'thumbnail', 'pages', 'file_type')
            ->orderBy('id', 'DESC')
            ->simplePaginate($settings->get('new-documents.total_documents', 12));

        // Set placeholders
        $placeholdersKeys = [
            '[app_name]',
            '[current_url]',
        ];
        $placeholdersValues = [
            $settings->getFinal('website.name', config('app.name')),
            route('new-documents'),
        ];

        $settings->setPlaceholders($placeholdersKeys, $placeholdersValues);
        // [END] Set placeholders

        return view('main.content.new-documents', compact('documents', 'settings'));
    }
}
