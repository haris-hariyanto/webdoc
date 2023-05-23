<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Helpers\Settings;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->query('q');
        if (empty($searchQuery)) {
            return redirect()->route('index');
        }

        $settings = new Settings(['website', 'search']);

        $documents = Document::where(function ($query) use ($searchQuery) {
                $searchQuery = preg_replace('/\s+/', ' ', $searchQuery);
                $searchQueries = explode(' ', $searchQuery);

                foreach ($searchQueries as $searchQuery) {
                    $query->where('title', 'LIKE', '%' . $searchQuery . '%');
                }
            })
            ->orderBy('views', 'DESC')
            ->simplePaginate($settings->getFinal('search.total_documents'))
            ->withQueryString();

        // Set placeholders
        $placeholdersKeys = [
            '[app_name]',
            '[current_url]',
            '[keyword]',
        ];
        $placeholdersValues = [
            $settings->getFinal('website.name', config('app.name')),
            route('index'),
            $searchQuery,
        ];

        $settings->setPlaceholders($placeholdersKeys, $placeholdersValues);
        // [END] Set placeholders
        
        return view('main.content.search', compact('documents', 'searchQuery', 'settings'));
    }
}
