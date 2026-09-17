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
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedSmallInteger('purchase_year')->nullable()->after('propiedad');
            $table->string('invoice_path')->nullable()->after('purchase_year');
            $table->boolean('is_verified')->default(true)->after('invoice_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['purchase_year', 'invoice_path', 'is_verified']);
        });
    }
};
