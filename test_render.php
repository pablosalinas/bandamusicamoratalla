<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $settings = \App\Models\SiteSetting::all()->pluck('value', 'key')->toArray();
    // Default values if some keys are missing
    $settings = array_merge([
        'band_name' => 'Banda',
        'session_timeout' => 120,
        'carousel_speed' => 4,
    ], $settings);
    
    echo view('admin.settings.index', [
        'settings' => $settings,
        'backupPassword' => 'test',
        'bandIban' => 'test',
        'logos' => [],
        'errors' => new \Illuminate\Support\ViewErrorBag()
    ])->render();
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
