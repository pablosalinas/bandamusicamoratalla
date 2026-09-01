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
            $isBoardAdminOrTreasurer = $user->isCurrentBoardMember() && in_array($user->role, ['admin', 'treasurer']);
            if ($isBoardAdminOrTreasurer || $user->isSuperAdmin()) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permisos. Solo superusuarios, o miembros activos de la junta que sean administradores o tesoreros pueden acceder a contabilidad.');
    }
}
