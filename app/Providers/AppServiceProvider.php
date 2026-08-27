<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                $timeout = \App\Models\SiteSetting::getSetting('session_timeout', 120);
                config(['session.lifetime' => $timeout]);

                $bandName = \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla');
                \Illuminate\Support\Facades\View::share('globalBandName', $bandName);
                
                $globalStatutes = \App\Models\SiteSetting::getSetting('statutes', '');
                \Illuminate\Support\Facades\View::share('globalStatutes', $globalStatutes);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('globalBandName', 'Banda de Música de Moratalla');
            \Illuminate\Support\Facades\View::share('globalStatutes', '');
        }
    }
}
