<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            // Force HTTPS and use the real incoming host so asset() URLs
            // are correct on Render regardless of APP_URL in .env
            URL::forceScheme('https');
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }
    }
}