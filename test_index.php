<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sheet = App\Models\SheetMusic::first();
if($sheet) {
    echo "Sheet: " . $sheet->title . "\n";
    $route = route('admin.sheet-music.view', $sheet);
    echo "Route: " . $route . "\n";
} else {
    echo "No sheets found.\n";
}
