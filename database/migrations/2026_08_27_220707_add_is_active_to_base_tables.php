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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
            $table->string('leave_reason')->nullable()->after('is_active');
        });

        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('pdf_path');
        });

        Schema::table('instrument_catalogs', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'leave_reason']);
        });

        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('instrument_catalogs', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
