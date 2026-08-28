<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                \Log::info('RedirectIfAuthenticated triggered. User is logged in. Redirecting to dashboard.');
                if (in_array(Auth::guard($guard)->user()->role, ['admin', 'treasurer'])) {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }
        
        \Log::info('RedirectIfAuthenticated NOT triggered. User is guest. Continuing.');

        return $next($request);
    }
}
