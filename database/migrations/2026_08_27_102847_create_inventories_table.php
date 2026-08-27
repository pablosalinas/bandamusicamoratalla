<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_catalog_id')->constrained('instrument_catalogs')->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->enum('status', ['good', 'repair', 'bad'])->default('good');
            $table->enum('owner_type', ['band', 'musician'])->default('band');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Current possessor or owner
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
