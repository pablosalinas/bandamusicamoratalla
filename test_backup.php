<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$r = response()->download(base_path('.env'));
$r->headers->setCookie(cookie('backup_downloaded', '1', 1, null, null, false, false));
echo "OK!";
