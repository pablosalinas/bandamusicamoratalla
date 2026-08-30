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
        Schema::table('musician_instruments', function (Blueprint $table) {
            $table->foreignId('instrument_brand_id')->nullable()->constrained('instrument_brands')->nullOnDelete();
            $table->string('modelo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_instruments', function (Blueprint $table) {
            $table->dropForeign(['instrument_brand_id']);
            $table->dropColumn(['instrument_brand_id', 'modelo']);
        });
    }
};
