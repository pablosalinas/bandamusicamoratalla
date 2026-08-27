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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MusicianController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/sheet-music/{sheetMusic}/download', [\App\Http\Controllers\MusicianController::class, 'download'])->name('musician.sheet-music.download');
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
    Route::get('/boards', function() { return 'En construcción'; })->name('boards.index');
    Route::get('/news', function() { return 'En construcción'; })->name('news.index');
});
require __DIR__.'/auth.php';
