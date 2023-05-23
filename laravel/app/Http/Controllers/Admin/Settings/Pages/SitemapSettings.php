<?php

namespace App\Http\Controllers\Admin\Settings\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Settings;
use App\Models\Setting;

class SitemapSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Page Settings') . ' : ' . __('Sitemap') => '',
        ];

        $settings = new Settings('sitemap');

        return view('admin.settings.pages.sitemap', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'total_items' => ['required', 'integer', 'min:1'],
            'change_frequency' => ['nullable'],
            'priority' => ['nullable'],
        ]);

        $settingsKey = [
            'total_items',
            'change_frequency',
            'priority',
        ];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'sitemap',
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
