<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountingAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isCurrentBoardMember() || in_array($user->role, ['admin', 'treasurer']) || $user->isSuperAdmin()) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos para acceder a esta sección. Solo la junta directiva activa, tesoreros y administradores pueden acceder.');
    }
}
