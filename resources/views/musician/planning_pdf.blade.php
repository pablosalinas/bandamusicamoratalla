<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planning de Actividades - {{ config('app.name', 'Banda de Música') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,800&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Outfit', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; background: white; color: black; }
        @media print {
            @page { margin: 0; }
            body { padding: 0; margin: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; line-height: 1.3 !important; }
            .no-print { display: none !important; }
            .print-container { padding: 1cm; }
            .page-break { page-break-before: always; }
            .watermark {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30%;
            opacity: 0.15;
            z-index: 9999;
            pointer-events: none;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-900" onload="window.print()">
    @php
        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $logos = [];
        foreach ($rawLogos as $logo) {
            if (is_string($logo)) $logos[] = ['path' => $logo, 'order' => 999];
            else if (is_array($logo)) $logos[] = $logo;
        }
        usort($logos, function($a, $b) { return ($a['order'] ?? 999) <=> ($b['order'] ?? 999); });
        $logos = array_column($logos, 'path');

        $primaryLogo = count($logos) > 0 ? $logos[0] : 'images/logo.jpg';
        $logoSrc = str_starts_with($primaryLogo, 'images/') ? asset($primaryLogo) : asset('storage/' . $primaryLogo);
        $bandName = \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música');
    @endphp

    <img src="{{ $logoSrc }}" class="watermark" alt="Watermark">
    
    <div class="max-w-4xl mx-auto p-8 print-container">
        
        <div class="flex justify-end items-center mb-8">
            <div class="text-right no-print flex gap-2 justify-end">
                <button onclick="window.close()" class="px-4 py-2 bg-gray-600 text-white rounded shadow hover:bg-gray-500 font-semibold">
                    Cerrar y Regresar
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-amber-600 text-white rounded shadow hover:bg-amber-500 font-semibold">
                    Imprimir / Guardar como PDF
                </button>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center text-center border-b-2 border-amber-600 pb-5 mb-8">
            <img src="{{ $logoSrc }}" alt="Logo" class="max-h-20 mb-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $bandName }}</h2>
                <h1 class="text-2xl font-extrabold text-amber-600 mt-1 uppercase tracking-wider">PLANNING DE ACTIVIDADES</h1>
                <p class="text-sm text-gray-500 mt-1">Generado el {{ now()->format('d/m/Y') }}</p>
            </div>
        </div>

        @php
            \Carbon\Carbon::setLocale('es');
            $legend = [
                'ensayo' => ['bg' => '#F3F4F6', 'text' => '#374151', 'label' => 'Ensayo'],           // Gray
                'contratada' => ['bg' => '#DCFCE7', 'text' => '#166534', 'label' => 'Contratada'],   // Green
                'convenio' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'label' => 'Convenio'],       // Blue
                'propias' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Propias'],         // Amber
                'salida' => ['bg' => '#F3E8FF', 'text' => '#6B21A8', 'label' => 'Salida'],           // Purple
                'otros' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'label' => 'Otros']              // Indigo (Default)
            ];
            
            $getColor = function($type) use ($legend) {
                $type = strtolower($type);
                if (in_array($type, ['propias', 'propia'])) return $legend['propias'];
                return $legend[$type] ?? $legend['otros'];
            };
        @endphp

        <!-- Leyenda -->
        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm">
            <p class="font-bold mb-2 text-gray-800">Leyenda de Tipos de Actividad:</p>
            <div class="flex flex-wrap gap-4">
                @foreach($legend as $key => $colors)
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $colors['bg'] }}; border: 1px solid {{ $colors['text'] }}; -webkit-print-color-adjust: exact; print-color-adjust: exact;"></span>
                        <span class="capitalize font-semibold text-gray-700">{{ $colors['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @forelse($events as $month => $monthEvents)
            <div class="mb-10" style="break-inside: avoid;">
                <h2 class="text-xl font-bold text-gray-800 border-b-2 border-gray-900 pb-1 mb-3 capitalize">{{ \Carbon\Carbon::parse($monthEvents->first()->event_date)->translatedFormat('F Y') }}</h2>
                
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="py-2 px-3 font-semibold text-gray-700 w-1/4">Fecha y Hora</th>
                            <th class="py-2 px-3 font-semibold text-gray-700 w-auto">Actividad</th>
                            <th class="py-2 px-3 font-semibold text-gray-700 text-center w-32">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($monthEvents as $event)
                            <tr>
                                <td class="py-2 px-3 font-medium text-gray-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d/m/Y') }} <span class="text-gray-500 ml-1">({{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }})</span>
                                </td>
                                <td class="py-2 px-3 font-semibold text-gray-900 truncate">
                                    {{ $event->name }}
                                </td>
                                <td class="py-2 px-3 text-center">
                                    @php
                                        $c = $getColor($event->type);
                                    @endphp
                                    <span class="inline-block px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap" style="background-color: {{ $c['bg'] }}; color: {{ $c['text'] }};">
                                        {{ $event->type }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">No hay actividades programadas próximamente.</p>
        @endforelse

    </div>

</body>
</html>
