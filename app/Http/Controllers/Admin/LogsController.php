<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Visit::query();

        // Default: last 7 days if no dates provided
        $dateFrom = $request->input('date_from', now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        
        $query->whereDate('visited_at', '>=', $dateFrom)
              ->whereDate('visited_at', '<=', $dateTo);

        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }
        
        if ($request->filled('path')) {
            $query->where('path', 'like', '%' . $request->path . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        $visits = $query->orderBy('visited_at', 'desc')->paginate(20, ['*'], 'visits_page')->appends($request->all());
        
        $activityLogs = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20, ['*'], 'activity_page');
        
        return view('admin.logs.index', compact('visits', 'activityLogs', 'dateFrom', 'dateTo'));
    }
}
