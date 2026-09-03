<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alter inventories table
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['brand', 'owner_type']);
            $table->foreignId('instrument_brand_id')->nullable()->constrained('instrument_brands')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->string('tipo_partitura')->nullable();
            $table->string('propiedad')->default('banda'); // 'banda' or 'musico'
        });

        // 2. Create inventory_movements table
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type'); // assigned, returned, transferred
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Alter instrument_photos to point to inventory_id
        Schema::table('instrument_photos', function (Blueprint $table) {
            $table->foreignId('inventory_id')->nullable()->constrained('inventories')->onDelete('cascade');
        });

        // 4. Data Migration
        $oldPivots = DB::table('musician_instruments')->get();
        
        foreach ($oldPivots as $pivot) {
            // Create the new inventory record
            $inventoryId = DB::table('inventories')->insertGetId([
                'instrument_catalog_id' => $pivot->instrument_catalog_id,
                'serial_number' => $pivot->serial_number,
                'model' => $pivot->modelo,
                'status' => 'good',
                // 'user_id' => $pivot->is_active ? $pivot->user_id : null, // assigned to user if active
                'notes' => 'Migrado desde ficha de músico',
                'created_at' => $pivot->created_at ?? now(),
                'updated_at' => $pivot->updated_at ?? now(),
                'instrument_brand_id' => $pivot->instrument_brand_id,
                'is_active' => $pivot->is_active,
                'tipo_partitura' => $pivot->tipo_partitura,
                'propiedad' => $pivot->propiedad ?? 'banda',
            ]);

            // If it was assigned (active), log the movement
            if ($pivot->is_active) {
                DB::table('inventory_movements')->insert([
                    'inventory_id' => $inventoryId,
                    'from_user_id' => null,
                    'to_user_id' => $pivot->user_id,
                    'type' => 'assigned',
                    'notes' => 'Asignación inicial (migración)',
                    'created_at' => $pivot->created_at ?? now(),
                    'updated_at' => $pivot->updated_at ?? now(),
                ]);
            }

            // Move photos
            DB::table('instrument_photos')
                ->where('musician_instrument_id', $pivot->id)
                ->update(['inventory_id' => $inventoryId]);
        }

        // 5. Clean up old table and columns
        Schema::table('instrument_photos', function (Blueprint $table) {
            $table->dropForeign(['musician_instrument_id']);
            $table->dropColumn('musician_instrument_id');
        });

        Schema::dropIfExists('musician_instruments');
    }

    public function down(): void
    {
        // Not providing a full rollback for data migration, just schema.
        // It's a one-way architectural change.
    }
};
