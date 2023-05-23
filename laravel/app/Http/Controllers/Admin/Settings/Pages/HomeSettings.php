<?php

namespace App\Http\Controllers\Admin\Settings\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Settings;
use App\Models\Setting;

class HomeSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Page Settings') . ' : ' . __('Home') => '',
        ];

        $settings = new Settings('home');

        return view('admin.settings.pages.home', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'main_heading' => ['required'],
            'brief_paragraph' => ['nullable'],
            'popular_documents_subheading' => ['required'],
            'popular_documents_paragraph' => ['nullable'],
            'popular_documents_total' => ['required', 'integer', 'min:1'],
            'new_documents_subheading' => ['required'],
            'new_documents_paragraph' => ['nullable'],
            'new_documents_total' => ['required', 'integer', 'min:1'],
            'article' => ['nullable'],
            'meta_data' => ['nullable'],
        ]);

        $settingsKey = [
            'title', 
            'main_heading', 
            'brief_paragraph', 
            'popular_documents_subheading', 
            'popular_documents_paragraph', 
            'popular_documents_total', 
            'new_documents_subheading', 
            'new_documents_paragraph', 
            'new_documents_total',
            'article',
            'meta_data',
        ];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'home',
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
