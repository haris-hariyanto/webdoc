<?php

namespace App\Http\Controllers\Main\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Settings;
use App\Models\Document;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemaps = [];

        $settings = new Settings(['sitemap']);

        $limit = $settings->get('sitemap.total_items', 3000);

        $documentsCount = Document::count();
        $documentsSitemap = ceil($documentsCount / $limit);
        for ($i = 1; $i <= $documentsSitemap; $i++) {
            $sitemaps[] = route('sitemap.documents', [$i]);
        }

        return response()->view('main.misc.sitemap.sitemaps-index', compact('sitemaps'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapDocuments($index)
    {
        $settings = new Settings(['sitemap', 'document']);

        $limit = $settings->get('sitemap.total_items', 3000);
        $skip = ($index - 1) * $limit;

        $documents = Document::orderBy('id', 'ASC')
            ->skip($skip)
            ->take($limit)
            ->get();

        $urls = [];
        foreach ($documents as $document) {
            $url = [];
            $url['loc'] = route('document', [$document->{$settings->get('document.slug')}]);
            $url['lastmod'] = $document->created_at->format('Y-m-d');

            if ($settings->get('sitemap.change_frequency')) {
                $url['changefreq'] = $settings->get('sitemap.change_frequency');
            }

            if ($settings->get('sitemap.priority')) {
                $url['priority'] = $settings->get('sitemap.priority');
            }

            $urls[] = $url;
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }
}
