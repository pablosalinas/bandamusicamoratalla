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
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'leave_reason')) {
                $table->string('leave_reason')->nullable()->after('is_active');
            }
        });

        Schema::table('sheet_musics', function (Blueprint $table) {
            if (!Schema::hasColumn('sheet_musics', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('pdf_path');
            }
            if (!Schema::hasColumn('sheet_musics', 'leave_reason')) {
                $table->string('leave_reason')->nullable()->after('is_active');
            }
        });

        Schema::table('instrument_catalogs', function (Blueprint $table) {
            if (!Schema::hasColumn('instrument_catalogs', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
            if (!Schema::hasColumn('instrument_catalogs', 'leave_reason')) {
                $table->string('leave_reason')->nullable()->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('users', 'leave_reason')) {
                $table->dropColumn('leave_reason');
            }
        });

        Schema::table('sheet_musics', function (Blueprint $table) {
            if (Schema::hasColumn('sheet_musics', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('sheet_musics', 'leave_reason')) {
                $table->dropColumn('leave_reason');
            }
        });

        Schema::table('instrument_catalogs', function (Blueprint $table) {
            if (Schema::hasColumn('instrument_catalogs', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('instrument_catalogs', 'leave_reason')) {
                $table->dropColumn('leave_reason');
            }
        });
    }
};
