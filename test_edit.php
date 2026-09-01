<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $sheetMusic = App\Models\SheetMusic::first();
    if (!$sheetMusic) { echo "No sheet music exists.\n"; exit; }
    \Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag());
    $controller = app(App\Http\Controllers\Admin\SheetMusicController::class);
    echo $controller->edit($sheetMusic)->render();
} catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}
