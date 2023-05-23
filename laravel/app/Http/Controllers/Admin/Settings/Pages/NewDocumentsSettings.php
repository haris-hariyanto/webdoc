<?php

namespace App\Http\Controllers\Admin\Settings\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Settings;
use App\Models\Setting;
use Illuminate\Support\Str;

class NewDocumentsSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Page Settings') . ' : ' . __('New Documents') => '',
        ];

        $settings = new Settings('new-documents');

        return view('admin.settings.pages.new-documents', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'permalink' => ['required'],
            'title' => ['required'],
            'main_heading' => ['required'],
            'brief_paragraph' => ['nullable'],
            'total_documents' => ['required', 'integer', 'min:1'],
            'article' => ['nullable'],
            'meta_data' => ['nullable'],
        ]);

        $validated['permalink'] = Str::slug($validated['permalink']);

        $settingsKey = [
            'permalink',
            'title',
            'main_heading',
            'brief_paragraph',
            'total_documents',
            'article',
            'meta_data',
        ];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'new-documents',
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
