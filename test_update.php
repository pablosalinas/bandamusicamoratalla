<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sheetMusic = App\Models\SheetMusic::first();
if ($sheetMusic) {
    $sheetMusic->update(['work_type' => 'Zarzuela']);
    echo "work_type is now: " . $sheetMusic->work_type . "\n";
}
