<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        $visits = \App\Models\Visit::orderBy('visited_at', 'desc')->paginate(20, ['*'], 'visits_page');
        $activityLogs = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20, ['*'], 'activity_page');
        
        return view('admin.logs.index', compact('visits', 'activityLogs'));
    }
}
