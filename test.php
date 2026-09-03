<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
try {
    $inv = \App\Models\Inventory::first();
    if (!$inv) { echo "No inventory items\n"; exit; }
    $view = app()->make('App\Http\Controllers\Admin\InventoryController')->edit($inv);
    echo $view->render();
} catch (\Exception $e) {
    echo $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}
