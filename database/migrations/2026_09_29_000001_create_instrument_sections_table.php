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
        Schema::create('instrument_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('instrument_sections')->nullOnDelete();
            $table->string('name'); // Viento Madera, Viento Metal, Clarinetes, Trompetas...
            $table->integer('order_index')->default(0); // Para ordenar cuerdas y subcuerdas
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Añadir instrument_section_id a instrument_catalogs
        Schema::table('instrument_catalogs', function (Blueprint $table) {
            $table->foreignId('instrument_section_id')->nullable()->after('type')->constrained('instrument_sections')->nullOnDelete();
            $table->integer('order_index')->default(0)->after('instrument_section_id');
        });

        // Añadir instrument_section_id a users (cuerda/subcuerda principal del músico)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('instrument_section_id')->nullable()->after('role')->constrained('instrument_sections')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['instrument_section_id']);
            $table->dropColumn('instrument_section_id');
        });

        Schema::table('instrument_catalogs', function (Blueprint $table) {
            $table->dropForeign(['instrument_section_id']);
            $table->dropColumn(['instrument_section_id', 'order_index']);
        });

        Schema::dropIfExists('instrument_sections');
    }
};
