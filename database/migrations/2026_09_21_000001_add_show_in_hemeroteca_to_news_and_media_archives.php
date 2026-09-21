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
        if (Schema::hasTable('news_activities') && !Schema::hasColumn('news_activities', 'show_in_hemeroteca')) {
            Schema::table('news_activities', function (Blueprint $table) {
                $table->boolean('show_in_hemeroteca')->default(true)->after('is_published');
            });
        }

        if (Schema::hasTable('media_archives') && !Schema::hasColumn('media_archives', 'show_in_hemeroteca')) {
            Schema::table('media_archives', function (Blueprint $table) {
                $table->boolean('show_in_hemeroteca')->default(true)->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('news_activities') && Schema::hasColumn('news_activities', 'show_in_hemeroteca')) {
            Schema::table('news_activities', function (Blueprint $table) {
                $table->dropColumn('show_in_hemeroteca');
            });
        }

        if (Schema::hasTable('media_archives') && Schema::hasColumn('media_archives', 'show_in_hemeroteca')) {
            Schema::table('media_archives', function (Blueprint $table) {
                $table->dropColumn('show_in_hemeroteca');
            });
        }
    }
};
