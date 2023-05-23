<?php

namespace App\Http\Controllers\Admin\Settings\Scrapers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Helpers\Settings;
use App\Models\Cookie;

class ScribdSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Scribd') => '',
        ];

        $settings = new Settings('scribd');

        return view('admin.settings.scrapers.scribd', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'cookies' => ['nullable'],
        ]);

        $settingsKey = ['cookies'];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'scribd',
                    'website' => config('app.code'),
                ],
                [
                    'value' => $validated[$settingKey],
                ]
            );
        }

        Cookie::where('website', 'scribd')->delete();
        if (!empty($validated['cookies'])) {
            $cookies = preg_split('/\r\n|\r|\n/', $validated['cookies']);
            foreach ($cookies as $cookie) {
                if (!empty(trim($cookie))) {
                    Cookie::create([
                        'website' => 'scribd',
                        'cookie' => $cookie,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', __('Settings have been updated!'));
    }
}
