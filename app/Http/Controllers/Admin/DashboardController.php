<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SheetMusic;
use App\Models\Board;
use App\Models\NewsActivity;
use App\Models\Inventory;
use App\Models\Event;
use App\Models\InstrumentCatalog;
use App\Models\MediaArchive;
use App\Models\InstrumentBrand;
use App\Models\FiscalYear;
use App\Models\Visit;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'sheet_music' => SheetMusic::count(),
            'board_members' => Board::count(),
            'news' => NewsActivity::count(),
            'inventory' => Inventory::count(),
            'events' => Event::count(),
            'instruments' => InstrumentCatalog::count(),
            'media' => MediaArchive::count(),
            'instrument_brands' => InstrumentBrand::count(),
            'fiscal_years' => FiscalYear::count(),
            'visits' => Visit::count(),
            'logs' => ActivityLog::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
