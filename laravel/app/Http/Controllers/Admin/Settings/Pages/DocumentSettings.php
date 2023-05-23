<?php

namespace App\Http\Controllers\Admin\Settings\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Settings;
use App\Models\Setting;
use App\Models\Document;
use Illuminate\Support\Str;

class DocumentSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Page Settings') . ' : ' . __('Document') => '',
        ];

        $settings = new Settings('document');

        $pageExample = Document::first();
        if ($pageExample) {
            $pageExample = route('document', [$pageExample->{$settings->get('slug')}]);
        }
        else {
            $pageExample = false;
        }

        return view('admin.settings.pages.document', compact('breadcrumb', 'settings', 'pageExample'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'permalink' => ['required'],
            'title' => ['required'],
            'main_heading' => ['required'],
            'show_preview' => ['required', 'in:Y,N'],
            'related_documents' => ['required', 'integer', 'min:1'],
            'meta_data' => ['nullable'],
            'slug' => ['required', 'in:slug,alt_slug_1,alt_slug_2,alt_slug_3,alt_slug_4,alt_slug_5'],
        ]);

        $validated['permalink'] = Str::slug($validated['permalink']);

        $settingsKey = [
            'permalink',
            'title',
            'main_heading',
            'show_preview',
            'related_documents',
            'meta_data',
            'slug',
        ];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'document',
                    'website' => config('app.code'),
                ],
                [
                    'value' => $validated[$settingKey],
                ]
            );
        }

        $settingsKey = ['slug'];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'website',
                    'website' => config('app.code'),
                ],
                [
                    'value' => $validated[$settingKey],
                ],
            );
        }

        return redirect()->back()->with('success', __('Settings have been updated!'));
    }
}
