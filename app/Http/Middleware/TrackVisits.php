<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (! $request->ajax() && ! $request->is('build/*') && ! $request->is('api/*')) {
                \App\Models\Visit::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'path' => $request->path(),
                    'visited_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignore if DB is not ready yet
        }

        return $next($request);
    }
}
