<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstrumentSection;
use App\Models\InstrumentCatalog;

class InstrumentSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definición de Cuerdas (principales) y Subcuerdas
        $sections = [
            [
                'name' => 'VIENTO MADERA',
                'order_index' => 1,
                'children' => [
                    [
                        'name' => 'Flautas y Flautines',
                        'order_index' => 1,
                        'instruments' => ['FLAUTÍN', 'FLAUTA']
                    ],
                    [
                        'name' => 'Oboes y Corno Inglés',
                        'order_index' => 2,
                        'instruments' => ['OBOE', 'CORNO INGLÉS']
                    ],
                    [
                        'name' => 'Fagotes',
                        'order_index' => 3,
                        'instruments' => ['FAGOT']
                    ],
                    [
                        'name' => 'Clarinetes',
                        'order_index' => 4,
                        'instruments' => ['REQUINTO', 'CLARINETE', 'CLARINETE BAJO']
                    ],
                    [
                        'name' => 'Saxofones',
                        'order_index' => 5,
                        'instruments' => ['SAXOFÓN SOPRANO', 'SAXOFÓN ALTO', 'SAXOFÓN TENOR', 'SAXOFÓN BARÍTONO']
                    ],
                ]
            ],
            [
                'name' => 'VIENTO METAL',
                'order_index' => 2,
                'children' => [
                    [
                        'name' => 'Trompetas y Fliscornos',
                        'order_index' => 1,
                        'instruments' => ['TROMPETA', 'FLISCORNO']
                    ],
                    [
                        'name' => 'Trompas',
                        'order_index' => 2,
                        'instruments' => ['TROMPA']
                    ],
                    [
                        'name' => 'Trombones',
                        'order_index' => 3,
                        'instruments' => ['TROMBÓN', 'TROMBÓN BAJO']
                    ],
                    [
                        'name' => 'Bombardinos',
                        'order_index' => 4,
                        'instruments' => ['BOMBARDINO']
                    ],
                    [
                        'name' => 'Tubas',
                        'order_index' => 5,
                        'instruments' => ['TUBA']
                    ],
                ]
            ],
            [
                'name' => 'PERCUSIÓN',
                'order_index' => 3,
                'children' => [
                    [
                        'name' => 'Percusión de Láminas / Teclados',
                        'order_index' => 1,
                        'instruments' => ['TIMBALES', 'XILÓFONO', 'MARIMBA', 'VIBRÁFONO', 'CAMPANAS TUBULARES', 'LIRA / GLOCKENSPIEL']
                    ],
                    [
                        'name' => 'Percusión Membranófonos / Batería',
                        'order_index' => 2,
                        'instruments' => ['CAJA', 'BOMBO', 'BATERÍA', 'BONGOS', 'CONGAS']
                    ],
                    [
                        'name' => 'Pequeña Percusión / Accesorios',
                        'order_index' => 3,
                        'instruments' => ['PLATOS', 'GONG / TAM-TAM', 'PANDERETA', 'TRIÁNGULO', 'CASTAÑUELAS', 'CLAVES']
                    ],
                ]
            ],
            [
                'name' => 'CUERDA',
                'order_index' => 4,
                'children' => [
                    [
                        'name' => 'Violonchelos y Contrabajos',
                        'order_index' => 1,
                        'instruments' => ['VIOLONCHELO', 'CONTRABAJO']
                    ],
                    [
                        'name' => 'Arpa y Guitarra',
                        'order_index' => 2,
                        'instruments' => ['ARPA']
                    ],
                ]
            ],
            [
                'name' => 'TECLA / OTROS',
                'order_index' => 5,
                'children' => [
                    [
                        'name' => 'Piano y Teclados',
                        'order_index' => 1,
                        'instruments' => ['PIANO']
                    ],
                ]
            ],
        ];

        $instrumentOrderCounter = 1;

        foreach ($sections as $secData) {
            $mainSection = InstrumentSection::updateOrCreate(
                ['name' => $secData['name'], 'parent_id' => null],
                [
                    'order_index' => $secData['order_index'],
                    'is_active' => true,
                ]
            );

            if (isset($secData['children'])) {
                foreach ($secData['children'] as $subSecData) {
                    $subSection = InstrumentSection::updateOrCreate(
                        ['name' => $subSecData['name'], 'parent_id' => $mainSection->id],
                        [
                            'order_index' => $subSecData['order_index'],
                            'is_active' => true,
                        ]
                    );

                    // Asociar instrumentos existentes o crearlos si no están
                    if (isset($subSecData['instruments'])) {
                        foreach ($subSecData['instruments'] as $instName) {
                            $catalogItem = InstrumentCatalog::where('name', $instName)->first();
                            if ($catalogItem) {
                                $catalogItem->update([
                                    'instrument_section_id' => $subSection->id,
                                    'type' => $mainSection->name,
                                    'order_index' => $instrumentOrderCounter++,
                                    'is_active' => true,
                                ]);
                            } else {
                                InstrumentCatalog::create([
                                    'name' => $instName,
                                    'type' => $mainSection->name,
                                    'instrument_section_id' => $subSection->id,
                                    'order_index' => $instrumentOrderCounter++,
                                    'is_active' => true,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // Asignar cuerdas/subcuerdas a los usuarios existentes que no tengan asignada
        // basándonos en los instrumentos de su inventario
        $users = \App\Models\User::with('inventories.instrument.section')->get();
        foreach ($users as $user) {
            if (!$user->instrument_section_id) {
                $firstInv = $user->inventories->first(fn($inv) => $inv->instrument && $inv->instrument->instrument_section_id);
                if ($firstInv && $firstInv->instrument->section) {
                    $user->update([
                        'instrument_section_id' => $firstInv->instrument->instrument_section_id
                    ]);
                }
            }
        }
    }
}
