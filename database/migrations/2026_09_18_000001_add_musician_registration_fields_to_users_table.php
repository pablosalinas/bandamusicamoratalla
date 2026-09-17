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
            $table->string('father_phone', 50)->nullable()->after('phone');
            $table->string('mother_phone', 50)->nullable()->after('father_phone');
            $table->string('guardian_phone', 50)->nullable()->after('mother_phone');
            $table->unsignedSmallInteger('joining_year')->nullable()->after('guardian_phone');
            $table->timestamp('privacy_accepted_at')->nullable()->after('joining_year');
            $table->string('registration_ip', 45)->nullable()->after('privacy_accepted_at');
            $table->text('registration_origin')->nullable()->after('registration_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'father_phone',
                'mother_phone',
                'guardian_phone',
                'joining_year',
                'privacy_accepted_at',
                'registration_ip',
                'registration_origin',
            ]);
        });
    }
};
