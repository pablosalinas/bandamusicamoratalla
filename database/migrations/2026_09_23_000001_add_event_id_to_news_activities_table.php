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
        if (Schema::hasTable('news_activities') && !Schema::hasColumn('news_activities', 'event_id')) {
            Schema::table('news_activities', function (Blueprint $table) {
                $table->foreignId('event_id')->nullable()->after('id')->constrained('events')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('news_activities') && Schema::hasColumn('news_activities', 'event_id')) {
            Schema::table('news_activities', function (Blueprint $table) {
                $table->dropForeign(['event_id']);
                $table->dropColumn('event_id');
            });
        }
    }
};
