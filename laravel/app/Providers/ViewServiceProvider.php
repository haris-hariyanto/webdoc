<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\Settings;

class ViewServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $settings = new Settings(['website', 'home']);

        View::share('__appName', $settings->get('website.name', config('app.name')));
        View::share('__topScripts', $settings->get('website.top_scripts'));
        View::share('__bottomScripts', $settings->get('website.bottom_scripts'));
    }

}