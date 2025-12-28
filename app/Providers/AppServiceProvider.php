<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- JANGAN LUPA IMPORT INI

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tambahkan logika ini:
        // Jika sedang pakai Ngrok (atau environment production), paksa HTTPS
        if (config('app.env') === 'local' || config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}