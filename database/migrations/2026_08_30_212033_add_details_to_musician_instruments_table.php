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
            $table->string('tipo_partitura')->nullable()->after('serial_number');
            $table->string('propiedad')->nullable()->after('tipo_partitura');
            $table->boolean('is_active')->default(true)->after('propiedad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musician_instruments', function (Blueprint $table) {
            $table->dropColumn(['tipo_partitura', 'propiedad', 'is_active']);
        });
    }
};
