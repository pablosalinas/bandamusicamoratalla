<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Totals
        $totalVisits = \App\Models\Visit::count();
        $visitsToday = \App\Models\Visit::whereDate('visited_at', now()->format('Y-m-d'))->count();
        $visitsThisWeek = \App\Models\Visit::whereDate('visited_at', '>=', now()->subDays(7)->format('Y-m-d'))->count();
        
        // Chart Data (Last 30 days)
        $chartData = \App\Models\Visit::selectRaw('DATE(visited_at) as date, COUNT(*) as count')
            ->whereDate('visited_at', '>=', now()->subDays(30)->format('Y-m-d'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $chartLabels = $chartData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d/m');
        });
        $chartValues = $chartData->pluck('count');

        // Top Paths
        $topPaths = \App\Models\Visit::selectRaw('path, COUNT(*) as count')
            ->groupBy('path')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Top Countries
        $topCountries = \App\Models\Visit::selectRaw('country, COUNT(*) as count')
            ->whereNotNull('country')
            ->where('country', '!=', 'Desconocido')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
            
        // Top Browsers
        $topBrowsers = \App\Models\Visit::selectRaw('browser, COUNT(*) as count')
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
            
        // Device Types
        $devices = \App\Models\Visit::selectRaw('device_type, COUNT(*) as count')
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->get();
        $desktopCount = $devices->where('device_type', 'desktop')->first()->count ?? 0;
        $mobileCount = $devices->where('device_type', 'mobile')->first()->count ?? 0;
        $totalDevices = $desktopCount + $mobileCount ?: 1; // Prevent division by zero
        $desktopPercent = round(($desktopCount / $totalDevices) * 100);
        $mobilePercent = round(($mobileCount / $totalDevices) * 100);

        return view('admin.analytics.index', compact(
            'totalVisits', 'visitsToday', 'visitsThisWeek',
            'chartLabels', 'chartValues',
            'topPaths', 'topCountries', 'topBrowsers',
            'desktopPercent', 'mobilePercent'
        ));
    }
}
