<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/estatutos', function () {
    return view('statutes');
})->name('estatutos');

Route::get('/migrate-db-secret', function() {
    try {
        // Ejecutamos migraciones
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = "Migraciones: " . \Illuminate\Support\Facades\Artisan::output() . "<br>";
        
        // Ejecutamos storage:link (si falla porque existe la carpeta public/storage que no es link, la borramos)
        $publicStorage = public_path('storage');
        if (file_exists($publicStorage) && !is_link($publicStorage)) {
            // Eliminar carpeta recursivamente
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicStorage, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
            rmdir($publicStorage);
        }
        
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $output .= "Storage Link: " . \Illuminate\Support\Facades\Artisan::output();
        
        return 'Ejecutado con éxito en producción.<br><br>Resultados:<br>' . $output;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    $news = \App\Models\NewsActivity::where('is_published', true)
        ->where(function ($query) {
            $query->whereNull('active_from')->orWhere('active_from', '<=', now()->toDateString());
        })
        ->where(function ($query) {
            $query->whereNull('active_to')->orWhere('active_to', '>=', now()->toDateString());
        })
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
        
    $band_history = \App\Models\SiteSetting::getSetting('band_history', '');
    
    // Visit counter logic
    $visit_count = (int) \App\Models\SiteSetting::getSetting('visit_count', 0);
    $visit_count++;
    \App\Models\SiteSetting::updateOrCreate(
        ['key' => 'visit_count'],
        ['value' => $visit_count, 'type' => 'integer']
    );

    $carouselMedia = \App\Models\CarouselMedia::orderBy('sort_order')->get();
    $carouselSpeed = (int) \App\Models\SiteSetting::getSetting('carousel_speed', 4);

    return view('welcome', compact('news', 'band_history', 'visit_count', 'carouselMedia', 'carouselSpeed'));
});

Route::view('/aviso-legal', 'legal')->name('legal');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MusicianController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/sheet-music/{sheetMusicInstrument}/download', [\App\Http\Controllers\MusicianController::class, 'download'])->name('musician.sheet-music.download');
    Route::get('/planning', [\App\Http\Controllers\MusicianController::class, 'planning'])->name('musician.planning');
    Route::get('/planning/pdf', [\App\Http\Controllers\MusicianController::class, 'planningPdf'])->name('musician.planning.pdf');
    Route::view('/manual', 'musician.manual')->name('musician.manual');
});

Route::get('/test-redirect', function () {
    return [
        'login' => route('login'),
        'dashboard' => route('dashboard'),
        'admin' => route('admin.dashboard'),
        'base' => url('/'),
    ];
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::view('/manual', 'admin.manual')->name('manual');
    
    // Rutas permitidas para Director
    Route::resource('sheet-music', \App\Http\Controllers\Admin\SheetMusicController::class)->parameters([
        'sheet-music' => 'sheetMusic'
    ]);
    Route::get('sheet-music/{sheetMusic}/download', [\App\Http\Controllers\Admin\SheetMusicController::class, 'download'])->name('sheet-music.download');
    Route::post('sheet-music/{sheetMusic}/upload-part-ajax', [\App\Http\Controllers\Admin\SheetMusicController::class, 'uploadPartAjax'])->name('sheet-music.upload-part-ajax');
    Route::get('sheet-music-parts/{sheetMusicInstrument}/download', [\App\Http\Controllers\Admin\SheetMusicController::class, 'downloadPart'])->name('sheet-music.download-part');
    
    // Instrument Catalog
    Route::resource('instruments', \App\Http\Controllers\Admin\InstrumentController::class)->except(['show']);
    Route::resource('instrument-brands', \App\Http\Controllers\Admin\InstrumentBrandController::class)->only(['index', 'store', 'destroy']);
    Route::put('instrument-photos/{photo}', [\App\Http\Controllers\Admin\InstrumentPhotoController::class, 'update'])->name('instrument-photos.update');
    Route::delete('instrument-photos/{photo}', [\App\Http\Controllers\Admin\InstrumentPhotoController::class, 'destroy'])->name('instrument-photos.destroy');
    
    // Inventory
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/pdf', [\App\Http\Controllers\Admin\InventoryController::class, 'pdf'])->name('inventory.pdf');

    // Sheet Music Catalog
    Route::get('sheet-music/pdf', [\App\Http\Controllers\Admin\SheetMusicController::class, 'pdf'])->name('sheet-music.pdf');
    Route::resource('sheet-music', \App\Http\Controllers\Admin\SheetMusicController::class)->parameters([
        'sheet-music' => 'sheetMusic'
    ]);
    Route::get('sheet-music/{sheetMusic}/download', [\App\Http\Controllers\Admin\SheetMusicController::class, 'download'])->name('sheet-music.download');
    Route::post('sheet-music/{sheetMusic}/upload-part-ajax', [\App\Http\Controllers\Admin\SheetMusicController::class, 'uploadPartAjax'])->name('sheet-music.upload-part-ajax');
    Route::get('sheet-music-parts/{sheetMusicInstrument}/download', [\App\Http\Controllers\Admin\SheetMusicController::class, 'downloadPart'])->name('sheet-music.download-part');
    
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
    Route::get('events/{event}/attendance', [\App\Http\Controllers\Admin\EventController::class, 'attendance'])->name('events.attendance');
    Route::post('events/{event}/attendance', [\App\Http\Controllers\Admin\EventController::class, 'storeAttendance'])->name('events.attendance.store');

    // Rutas RESTRINGIDAS (solo admin y treasurer)
    Route::middleware(['admin_or_treasurer'])->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        
        Route::resource('boards', \App\Http\Controllers\Admin\BoardController::class);
        Route::post('boards/{board}/members', [\App\Http\Controllers\Admin\BoardController::class, 'addMember'])->name('boards.members.add');
        Route::delete('boards/{board}/members/{member}', [\App\Http\Controllers\Admin\BoardController::class, 'removeMember'])->name('boards.members.remove');
        
        Route::resource('boards.minutes', \App\Http\Controllers\Admin\BoardMinuteController::class);
        Route::get('boards/{board}/minutes/{minute}/pdf', [\App\Http\Controllers\Admin\BoardMinuteController::class, 'downloadPdf'])->name('boards.minutes.pdf');
        
        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/carousel', [\App\Http\Controllers\Admin\SettingsController::class, 'storeCarouselMedia'])->name('settings.carousel.store');
        Route::put('settings/carousel/{media}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCarouselMedia'])->name('settings.carousel.update');
        Route::delete('settings/carousel/{media}', [\App\Http\Controllers\Admin\SettingsController::class, 'destroyCarouselMedia'])->name('settings.carousel.destroy');
        
        // Logos
        Route::post('settings/logos', [\App\Http\Controllers\Admin\SettingsController::class, 'storeLogo'])->name('settings.logos.store');
        Route::put('settings/logos', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLogoOrder'])->name('settings.logos.update');
        Route::delete('settings/logos', [\App\Http\Controllers\Admin\SettingsController::class, 'destroyLogo'])->name('settings.logos.destroy');

        // Analytics & Logs
        Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('logs', [\App\Http\Controllers\Admin\LogsController::class, 'index'])->name('logs.index');
    });

});

// Contabilidad (acceso para admin, treasurer, y junta directiva activa)
Route::middleware(['auth', 'accounting_access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('fiscal-years/{fiscalYear}/report', [\App\Http\Controllers\Admin\FiscalYearController::class, 'report'])->name('fiscal-years.report');
    Route::resource('fiscal-years', \App\Http\Controllers\Admin\FiscalYearController::class);
    Route::resource('fiscal-years.budget-movements', \App\Http\Controllers\Admin\BudgetMovementController::class)->except(['index', 'show'])->parameters([
        'fiscal-years' => 'fiscalYear',
        'budget-movements' => 'movement'
    ]);
});
require __DIR__.'/auth.php';

Route::get('/ejecutar-migraciones-secretas', function() {
    try {
        // Por precaución, borramos el archivo viejo si todavía existe en el servidor para que no intente ejecutarlo
        $oldMigration = database_path('migrations/2026_08_27_220741_create_attendances_table.php');
        if (file_exists($oldMigration)) {
            @unlink($oldMigration);
        }

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return 'Migraciones ejecutadas con éxito:<br><br>' . nl2br(htmlspecialchars($output));
    } catch (\Exception $e) {
        return '<b>ERROR durante las migraciones:</b><br><br>' . nl2br(htmlspecialchars($e->getMessage()));
    }
});

Route::get('/limpiar-cache', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return 'Caché de vistas y de aplicación limpiada con éxito.';
    } catch (\Exception $e) {
        return 'Error al limpiar caché: ' . $e->getMessage();
    }
});

Route::get('/crear-admin-secreto', function() {
    try {
        if (\App\Models\User::where('email', 'admin@bandamusicamoratalla.com')->exists()) {
            return 'El administrador ya existe.';
        }
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'Usuarios por defecto creados con exito.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/cargar-instrumentos', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'InstrumentSeeder',
            '--force' => true
        ]);
        return 'Instrumentos cargados masivamente con éxito.';
    } catch (\Exception $e) {
        return 'Error al cargar instrumentos: ' . $e->getMessage();
    }
});

Route::get('/debug-server', function() {
    $storage = storage_path();
    $isWritable = is_writable($storage) ? 'Si' : 'NO';
    $sessions = storage_path('framework/sessions');
    $sessionsWritable = is_writable($sessions) ? 'Si' : 'NO';
    $logs = storage_path('logs');
    $logsWritable = is_writable($logs) ? 'Si' : 'NO';
    return 'Storage Writable: ' . $isWritable . '<br>Sessions Writable: ' . $sessionsWritable . '<br>Logs Writable: ' . $logsWritable;
});
