<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instruments = [
            ['name' => 'FLAUTÍN', 'type' => 'VIENTO MADERA'],
            ['name' => 'FLAUTA', 'type' => 'VIENTO MADERA'],
            ['name' => 'OBOE', 'type' => 'VIENTO MADERA'],
            ['name' => 'CORNO INGLÉS', 'type' => 'VIENTO MADERA'],
            ['name' => 'FAGOT', 'type' => 'VIENTO MADERA'],
            ['name' => 'REQUINTO', 'type' => 'VIENTO MADERA'],
            ['name' => 'CLARINETE', 'type' => 'VIENTO MADERA'],
            ['name' => 'CLARINETE BAJO', 'type' => 'VIENTO MADERA'],
            ['name' => 'SAXOFÓN SOPRANO', 'type' => 'VIENTO MADERA'],
            ['name' => 'SAXOFÓN ALTO', 'type' => 'VIENTO MADERA'],
            ['name' => 'SAXOFÓN TENOR', 'type' => 'VIENTO MADERA'],
            ['name' => 'SAXOFÓN BARÍTONO', 'type' => 'VIENTO MADERA'],
            ['name' => 'TROMPA', 'type' => 'VIENTO METAL'],
            ['name' => 'TROMPETA', 'type' => 'VIENTO METAL'],
            ['name' => 'FLISCORNO', 'type' => 'VIENTO METAL'],
            ['name' => 'TROMBÓN', 'type' => 'VIENTO METAL'],
            ['name' => 'TROMBÓN BAJO', 'type' => 'VIENTO METAL'],
            ['name' => 'BOMBARDINO', 'type' => 'VIENTO METAL'],
            ['name' => 'TUBA', 'type' => 'VIENTO METAL'],
            ['name' => 'VIOLONCHELO', 'CUERDA'],
            ['name' => 'CONTRABAJO', 'CUERDA'],
            ['name' => 'CAJA', 'PERCUSIÓN'],
            ['name' => 'BOMBO', 'PERCUSIÓN'],
            ['name' => 'PLATOS', 'PERCUSIÓN'],
            ['name' => 'TIMBALES', 'PERCUSIÓN'],
            ['name' => 'XILÓFONO', 'PERCUSIÓN'],
            ['name' => 'LIRA / GLOCKENSPIEL', 'PERCUSIÓN'],
            ['name' => 'MARIMBA', 'PERCUSIÓN'],
            ['name' => 'VIBRÁFONO', 'PERCUSIÓN'],
            ['name' => 'CAMPANAS TUBULARES', 'PERCUSIÓN'],
            ['name' => 'GONG / TAM-TAM', 'PERCUSIÓN'],
            ['name' => 'PANDERETA', 'PERCUSIÓN'],
            ['name' => 'TRIÁNGULO', 'PERCUSIÓN'],
            ['name' => 'CASTAÑUELAS', 'PERCUSIÓN'],
            ['name' => 'CLAVES', 'PERCUSIÓN'],
            ['name' => 'BONGOS', 'PERCUSIÓN'],
            ['name' => 'CONGAS', 'PERCUSIÓN'],
            ['name' => 'BATERÍA', 'PERCUSIÓN'],
            ['name' => 'PIANO', 'TECLA'],
            ['name' => 'ARPA', 'CUERDA'],
        ];

        foreach ($instruments as $inst) {
            \App\Models\InstrumentCatalog::updateOrCreate(
                ['name' => $inst['name']],
                [
                    'type' => $inst[0] ?? $inst['type'] ?? 'PERCUSIÓN', // fallback for mixed syntax above
                    'is_active' => true,
                ]
            );
        }
    }
}
