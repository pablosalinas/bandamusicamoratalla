<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
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
    public function handle(Logout $event): void
    {
        try {
            if ($event->user) {
                \App\Models\ActivityLog::create([
                    'user_id' => $event->user->id,
                    'action' => 'logout',
                    'description' => 'Cierre de sesión exitoso',
                    'ip_address' => request()->ip(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignore if DB is not ready yet
        }
    }
}
