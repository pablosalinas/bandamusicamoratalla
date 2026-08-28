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

    return view('welcome', compact('news', 'band_history', 'visit_count'));
});

Route::view('/aviso-legal', 'legal')->name('legal');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MusicianController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/sheet-music/{sheetMusic}/download', [\App\Http\Controllers\MusicianController::class, 'download'])->name('musician.sheet-music.download');
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
    
    // Placeholder para futuras rutas
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('sheet-music', \App\Http\Controllers\Admin\SheetMusicController::class)->parameters([
        'sheet-music' => 'sheetMusic'
    ]);
    Route::get('sheet-music/{sheetMusic}/download', [\App\Http\Controllers\Admin\SheetMusicController::class, 'download'])->name('sheet-music.download');
    Route::resource('instruments', \App\Http\Controllers\Admin\InstrumentController::class);
    Route::resource('boards', \App\Http\Controllers\Admin\BoardController::class);
    Route::post('boards/{board}/members', [\App\Http\Controllers\Admin\BoardController::class, 'addMember'])->name('boards.members.add');
    Route::delete('boards/{board}/members/{member}', [\App\Http\Controllers\Admin\BoardController::class, 'removeMember'])->name('boards.members.remove');
    
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
    Route::get('events/{event}/attendance', [\App\Http\Controllers\Admin\EventController::class, 'attendance'])->name('events.attendance');
    Route::post('events/{event}/attendance', [\App\Http\Controllers\Admin\EventController::class, 'storeAttendance'])->name('events.attendance.store');
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Logs
    Route::get('logs', [\App\Http\Controllers\Admin\LogsController::class, 'index'])->name('logs.index');
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
