<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach(App\Models\FiscalYear::all() as $fy) {
    echo $fy->name . " | " . $fy->start_date . " | " . $fy->end_date . " | " . $fy->created_at . "\n";
}
