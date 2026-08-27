<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sheet_music', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('composer')->nullable();
            $table->string('arranger')->nullable();
            $table->string('pdf_file_path')->nullable(); // General score
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheet_music');
    }
};
