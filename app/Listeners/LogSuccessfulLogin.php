<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        try {
            \App\Models\ActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'login',
                'description' => 'Inicio de sesión exitoso',
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Ignore if DB is not ready yet
        }
    }
}
