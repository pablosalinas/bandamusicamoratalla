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

                // Configuración dinámica de Correo / SMTP
                $mailDriver = \App\Models\SiteSetting::getSetting('mail_mailer', '');
                if ($mailDriver === 'smtp') {
                    $mailHost = \App\Models\SiteSetting::getSetting('mail_host', 'smtp.ionos.es');
                    $mailPort = (int) \App\Models\SiteSetting::getSetting('mail_port', 587);
                    $mailEnc = \App\Models\SiteSetting::getSetting('mail_encryption', 'tls');
                    $mailUser = \App\Models\SiteSetting::getSetting('mail_username', '');
                    $mailFromAddress = \App\Models\SiteSetting::getSetting('mail_from_address', $mailUser);
                    $mailFromName = \App\Models\SiteSetting::getSetting('mail_from_name', $bandName);

                    $rawMailPass = \App\Models\SiteSetting::getSetting('mail_password', '');
                    $mailPass = '';
                    if ($rawMailPass) {
                        try {
                            $mailPass = \Illuminate\Support\Facades\Crypt::decryptString($rawMailPass);
                        } catch (\Exception $e) {
                            $mailPass = '';
                        }
                    }

                    if (!empty($mailHost) && !empty($mailUser)) {
                        config([
                            'mail.default' => 'smtp',
                            'mail.mailers.smtp.transport' => 'smtp',
                            'mail.mailers.smtp.host' => $mailHost,
                            'mail.mailers.smtp.port' => $mailPort,
                            'mail.mailers.smtp.encryption' => ($mailEnc === 'none' || empty($mailEnc)) ? null : $mailEnc,
                            'mail.mailers.smtp.username' => $mailUser,
                            'mail.mailers.smtp.password' => $mailPass,
                            'mail.from.address' => $mailFromAddress ?: $mailUser,
                            'mail.from.name' => $mailFromName ?: $bandName,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('globalBandName', 'Banda de Música de Moratalla');
            \Illuminate\Support\Facades\View::share('globalStatutes', '');
        }

        // Compartir contador de validaciones pendientes (músicos e inventario) en todas las vistas
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $pendingMusicians = 0;
            $pendingInstruments = 0;

            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    $pendingMusicians = \App\Models\User::where('users.is_active', false)->where('users.role', 'musician')->count();
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('inventories')) {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('inventories', 'is_verified')) {
                        $pendingInstruments = \App\Models\Inventory::where('is_verified', false)->count();
                    }
                }
            } catch (\Exception $e) {
                // Silencioso si aún no se han ejecutado las migraciones
            }

            $totalPending = $pendingMusicians + $pendingInstruments;
            $view->with('pendingMusiciansCount', $pendingMusicians)
                 ->with('pendingInstrumentsCount', $pendingInstruments)
                 ->with('totalPendingValidationsCount', $totalPending);
        });
    }
}
