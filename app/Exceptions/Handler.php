<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Captura error 419 (token CSRF caducado / sesión expirada) y redirige al login
        $this->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Si es una petición AJAX/fetch, devolver JSON con código 419
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión ha expirado. Por favor, recarga la página e inicia sesión de nuevo.',
                    'redirect' => route('login'),
                ], 419);
            }

            // Redirigir al login con mensaje de aviso
            return redirect()
                ->route('login')
                ->with('session_expired', 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.');
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
