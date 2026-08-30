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
                $ip = $request->ip();
                
                // Get geo data from cache or API
                $geo = \Illuminate\Support\Facades\Cache::remember('geo_'.$ip, 86400, function() use ($ip) {
                    if ($ip === '127.0.0.1' || $ip === '::1') return null;
                    try {
                        $response = \Illuminate\Support\Facades\Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=country,city");
                        if ($response->successful() && $response->json('country')) {
                            return [
                                'country' => $response->json('country'),
                                'city' => $response->json('city')
                            ];
                        }
                    } catch (\Exception $e) {}
                    return null;
                });
                
                // Simple parsing for browser/device
                $ua = $request->userAgent() ?? '';
                $deviceType = preg_match('/Mobile|Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i', $ua) ? 'mobile' : 'desktop';
                
                $browser = 'Unknown';
                if (preg_match('/Edg/i', $ua)) $browser = 'Edge';
                elseif (preg_match('/Firefox/i', $ua)) $browser = 'Firefox';
                elseif (preg_match('/OPR/i', $ua)) $browser = 'Opera';
                elseif (preg_match('/Chrome/i', $ua)) $browser = 'Chrome';
                elseif (preg_match('/Safari/i', $ua)) $browser = 'Safari';
                elseif (preg_match('/MSIE|Trident/i', $ua)) $browser = 'Internet Explorer';

                \App\Models\Visit::create([
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'path' => $request->path(),
                    'visited_at' => now(),
                    'country' => $geo['country'] ?? 'Desconocido',
                    'city' => $geo['city'] ?? 'Desconocida',
                    'browser' => $browser,
                    'device_type' => $deviceType,
                ]);
            }
        } catch (\Exception $e) {
            // Ignore if DB is not ready yet
        }

        return $next($request);
    }
}
