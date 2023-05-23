<?php

namespace App\Http\Controllers\Admin\Settings\Scrapers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Proxy;
use App\Helpers\Settings;

class ProxySettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Proxy') => '',
        ];

        $settings = new Settings('proxy');

        return view('admin.settings.scrapers.proxy', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'use_proxy' => ['required', 'in:Y,N'],
            'proxies' => ['nullable'],
        ]);

        $settingsKey = ['use_proxy', 'proxies'];
        foreach ($settingsKey as $settingKey) {
            Setting::updateOrCreate(
                [
                    'key' => $settingKey,
                    'type' => 'proxy',
                    'website' => config('app.code'),
                ],
                [
                    'value' => $validated[$settingKey],
                ]
            );
        }

        Proxy::truncate();
        if (!empty($validated['proxies'])) {
            $proxies = preg_split('/\r\n|\r|\n/', $validated['proxies']);
            foreach ($proxies as $proxy) {
                if (!empty(trim($proxy))) {
                    $proxy = explode(':', $proxy);
                    Proxy::create([
                        'ip' => trim($proxy[0]),
                        'port' => trim($proxy[1]),
                        'username' => trim($proxy[2]),
                        'password' => trim($proxy[3]),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', __('Settings have been updated!'));
    }
}
