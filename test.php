<?php
require __DIR__.'/vendor/autoload.php';
 = require_once __DIR__.'/bootstrap/app.php';
->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

 = new \App\Http\Controllers\Admin\SheetMusicController();
 = ->downloadAll(\App\Models\SheetMusic::first());
echo get_class();
