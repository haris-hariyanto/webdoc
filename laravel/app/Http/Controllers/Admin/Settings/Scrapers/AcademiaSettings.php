<?php

namespace App\Http\Controllers\Admin\Settings\Scrapers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Helpers\Settings;

class AcademiaSettings extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Academia') => '',
        ];

        $settings = new Settings('academia');

        return view('admin.settings.scrapers.academia', compact('breadcrumb', 'settings'));
    }

    public function save(Request $request)
    {

    }
}
