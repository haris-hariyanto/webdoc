<?php

namespace App\Http\Controllers\Admin\Settings\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Settings;
use App\Models\Setting;
use App\Models\Document;
use Illuminate\Support\Str;

class SearchSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Page Settings') . ' : ' . __('Search Page') => '',
        ];

        $settings = new Settings('search');

        $pageExample = Document::first();
        if ($pageExample) {
            $pageExample = route('search', ['q' => $pageExample->title]);
        }
        else {
            $pageExample = false;
        }

        return view('admin.settings.pages.search', compact('breadcrumb', 'settings', 'pageExample'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'permalink' => ['required'],
            'title' => ['required'],
            'main_heading' => ['required'],
            'brief_paragraph' => ['nullable'],
            'total_documents' => ['required', 'integer', 'min:1'],
            'meta_data' => ['nullable'],
        ]);

        $validated['permalink'] = Str::slug($validated['permalink']);

        $settingsKey = [
            'permalink',
            'title',
            'main_heading',
            'brief_paragraph',
            'total_documents',
            'meta_data',
        ];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'search',
                    'website' => config('app.code'),
                ],
                [
                    'value' => $validated[$settingKey],
                ]
            );
        }

        return redirect()->back()->with('success', __('Settings have been updated!'));
    }
}
