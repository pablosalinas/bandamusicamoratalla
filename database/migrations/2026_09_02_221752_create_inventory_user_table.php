<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create pivot table
        Schema::create('inventory_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Un usuario no debe tener dos veces la misma asignación directa simultánea en la pivot
            $table->unique(['inventory_id', 'user_id']);
        });

        // Migrate existing data
        $inventories = DB::table('inventories')->whereNotNull('user_id')->get();
        foreach ($inventories as $inv) {
            DB::table('inventory_user')->insert([
                'inventory_id' => $inv->id,
                'user_id' => $inv->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop user_id from inventories
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        });

        // Try to revert data (will only keep 1 user max per instrument)
        $pivots = DB::table('inventory_user')->get();
        foreach ($pivots as $pivot) {
            DB::table('inventories')
                ->where('id', $pivot->inventory_id)
                ->update(['user_id' => $pivot->user_id]);
        }

        Schema::dropIfExists('inventory_user');
    }
};
