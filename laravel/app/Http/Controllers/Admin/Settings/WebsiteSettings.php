<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Helpers\Settings;

class WebsiteSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Website Settings') => '',
        ];

        $settings = new Settings('website');

        return view('admin.settings.website', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:32'],
            'top_scripts' => ['nullable'],
            'bottom_scripts' => ['nullable'],
        ]);

        $settingsKey = ['name', 'top_scripts', 'bottom_scripts'];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'website',
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
