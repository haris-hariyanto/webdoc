<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Helpers\Settings;

class HomeController extends Controller
{
    public function index()
    {
        $files = [];
        for ($i = 1; $i <= 206; $i++) {
            $files[] = [
                'fileName' => 'Part ' . $i,
                'url' => 'https://s3.us-east-005.backblazeb2.com/brainus/contents-' . $i . '.zip',
            ];
        }
        // return $files;

        $settings = new Settings(['website', 'home']);

        $popularDocuments = Document::select('slug', 'alt_slug_1', 'alt_slug_2', 'alt_slug_3', 'alt_slug_4', 'alt_slug_5', 'title', 'thumbnail', 'pages', 'file_type')
            ->orderBy('views', 'DESC')
            ->take($settings->get('home.popular_documents_total', 12))
            ->get();

        $newDocuments = Document::select('slug', 'alt_slug_1', 'alt_slug_2', 'alt_slug_3', 'alt_slug_4', 'alt_slug_5', 'title', 'thumbnail', 'pages', 'file_type')
            ->orderBy('id', 'DESC')
            ->take($settings->get('home.new_documents_total', 12))
            ->get();
        
        // Set placeholders
        $placeholdersKeys = [
            '[app_name]',
            '[current_url]',
        ];
        $placeholdersValues = [
            $settings->getFinal('website.name', config('app.name')),
            route('index'),
        ];

        $settings->setPlaceholders($placeholdersKeys, $placeholdersValues);
        // [END] Set placeholders

        return view('main.index', compact('popularDocuments', 'newDocuments', 'settings'));
    }
}
