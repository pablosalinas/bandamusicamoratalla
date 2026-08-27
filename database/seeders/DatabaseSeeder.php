<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin',
            'last_name' => 'Banda',
            'email' => 'admin@bandamusicamoratalla.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Musician user
        User::create([
            'name' => 'Músico',
            'last_name' => 'Prueba',
            'email' => 'musico@bandamusicamoratalla.com',
            'password' => Hash::make('password123'),
            'role' => 'musician',
            'is_active' => true,
        ]);
        
        // Create External user
        User::create([
            'name' => 'Externo',
            'last_name' => 'Junta',
            'email' => 'externo@bandamusicamoratalla.com',
            'password' => Hash::make('password123'),
            'role' => 'external',
            'is_active' => true,
        ]);
    }
}
