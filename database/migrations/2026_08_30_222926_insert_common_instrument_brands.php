<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $brands = [
            'Yamaha', 'Buffet Crampon', 'Selmer', 'Vandoren', 'Bach', 
            'Jupiter', 'Pearl', 'Stomvi', 'B&S', 'Besson', 
            'Courtois', 'Yanagisawa', 'Fender', 'Gibson', 'Roland', 
            'Korg', 'Adams', 'Majestic', 'Ludwig', 'Meinl', 
            'Zildjian', 'Sabian'
        ];

        foreach ($brands as $brand) {
            \App\Models\InstrumentBrand::firstOrCreate(['name' => $brand]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
