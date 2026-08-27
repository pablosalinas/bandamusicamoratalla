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
        Schema::dropIfExists('attendances');
        
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('present'); // present, absent, excused
            $table->timestamps();
            
            $table->unique(['event_id', 'user_id']); // un usuario solo puede tener un registro por evento
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
