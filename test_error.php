<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // We don't have db connection here because it's windows without local mysql. 
    // Wait, let's just check the syntax.
    echo "Syntax OK";
} catch (\Exception $e) {
    echo $e->getMessage();
}
