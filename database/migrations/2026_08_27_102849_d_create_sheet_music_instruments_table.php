<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sheet_music_instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sheet_music_id')->constrained('sheet_music')->onDelete('cascade');
            $table->foreignId('instrument_catalog_id')->constrained('instrument_catalogs')->onDelete('cascade');
            $table->string('pdf_file_path')->nullable(); // Specific part for this instrument
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheet_music_instruments');
    }
};
