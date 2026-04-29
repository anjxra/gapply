<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Use our custom compact pagination on every paginated list
        Paginator::defaultView('pagination.custom');

        // Fix asset/route URLs when running behind a reverse proxy (Render, etc.)
        // Triggers on any HTTPS request regardless of APP_ENV setting
        if (!app()->runningInConsole()) {
            $proto = request()->header('X-Forwarded-Proto')
                  ?? request()->header('X-Forwarded-Ssl')
                  ?? (request()->isSecure() ? 'https' : 'http');

            if ($proto === 'https') {
                URL::forceScheme('https');
                URL::forceRootUrl('https://' . request()->getHost());
            }
        }
    }
}