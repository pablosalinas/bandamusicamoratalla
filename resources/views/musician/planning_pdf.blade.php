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
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
            .print-container { padding: 1cm; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-900" onload="window.print()">
    
    <div class="max-w-4xl mx-auto p-8 print-container">
        
        <div class="flex justify-between items-center border-b-2 border-gray-900 pb-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold uppercase tracking-widest text-gray-900">Planning de Actividades</h1>
                <p class="text-sm text-gray-500 mt-1">Generado el {{ now()->format('d/m/Y') }}</p>
            </div>
            <div class="text-right no-print">
                <button onclick="window.print()" class="px-4 py-2 bg-amber-600 text-white rounded shadow hover:bg-amber-500 font-semibold">
                    Imprimir / Guardar como PDF
                </button>
            </div>
        </div>

        @forelse($events as $month => $monthEvents)
            <div class="mb-10" style="break-inside: avoid;">
                <h2 class="text-2xl font-bold text-gray-800 border-b border-gray-300 pb-2 mb-4 capitalize">{{ $month }}</h2>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="py-3 px-4 font-semibold text-gray-700 w-1/3">Fecha</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 w-1/2">Actividad</th>
                            <th class="py-3 px-4 font-semibold text-gray-700 text-center w-1/6">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($monthEvents as $event)
                            <tr>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d/m/Y') }} <br>
                                    <span class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }}</span>
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-900">
                                    {{ $event->name }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-{{ $event->color }}-100 text-{{ $event->color }}-800">
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
