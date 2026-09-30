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
        // 1. Campo author en imágenes de noticias y eventos (news_images)
        if (Schema::hasTable('news_images') && !Schema::hasColumn('news_images', 'author')) {
            Schema::table('news_images', function (Blueprint $table) {
                $table->string('author')->nullable()->after('description');
            });
        }

        // 2. Campo author en imágenes de la galería multimedia / archivo sonoro (media_archive_images)
        if (Schema::hasTable('media_archive_images') && !Schema::hasColumn('media_archive_images', 'author')) {
            Schema::table('media_archive_images', function (Blueprint $table) {
                $table->string('author')->nullable()->after('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('news_images') && Schema::hasColumn('news_images', 'author')) {
            Schema::table('news_images', function (Blueprint $table) {
                $table->dropColumn('author');
            });
        }

        if (Schema::hasTable('media_archive_images') && Schema::hasColumn('media_archive_images', 'author')) {
            Schema::table('media_archive_images', function (Blueprint $table) {
                $table->dropColumn('author');
            });
        }
    }
};
