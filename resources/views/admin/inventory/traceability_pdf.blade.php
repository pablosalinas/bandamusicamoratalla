<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Trazabilidad - {{ $inventory->instrument->name ?? 'Instrumento' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: sans-serif; background: #fff; padding: 20px; }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30%;
            opacity: 0.15;
            z-index: 9999;
            pointer-events: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid #D97706;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header-text {
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header-text h1 {
            color: #D97706;
            margin: 5px 0 10px 0;
            font-size: 26px;
            text-transform: uppercase;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
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

    <img src="{{ $logoSrc }}" class="watermark">

    <div class="mb-6 flex justify-end items-center no-print">
        <div class="flex gap-2"><button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cerrar y Regresar</button><button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded">Imprimir / Guardar PDF</button></div>
    </div>

    <div class="header">
        <img src="{{ $logoSrc }}" alt="Logo">
        <div class="header-text">
            <h2>{{ $bandName }}</h2>
            <h1>TRAZABILIDAD: {{ mb_strtoupper($inventory->instrument->name ?? 'INSTRUMENTO') }}</h1>
            <div class="text-sm text-gray-700 mt-1">({{ $inventory->brand->name ?? '' }} {{ $inventory->model }})</div>
        </div>
    </div>

    <div class="mb-8 border border-gray-300 p-4 rounded bg-gray-50">
        <h3 class="font-bold text-lg mb-2">Datos del Instrumento</h3>
        <table class="w-full text-left">
            <tr><th class="py-1">Nº Serie:</th><td>{{ $inventory->serial_number ?: 'No especificado' }}</td></tr>
            <tr><th class="py-1">Propiedad:</th><td class="capitalize">{{ $inventory->propiedad }}</td></tr>
            <tr><th class="py-1">Estado Físico:</th><td>{{ ucfirst($inventory->status) }}</td></tr>
            <tr><th class="py-1">Situación Actual:</th>
                <td>
                    @if($inventory->users->count() > 0)
                        Asignado a: <span class="font-bold">{{ $inventory->users->map(fn($u) => $u->name . ' ' . $u->last_name)->implode(', ') }}</span>
                    @else
                        <span class="font-bold text-green-600">En stock (Disponible)</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <h3 class="font-bold text-xl mb-4">Historial de Movimientos</h3>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b-2 border-gray-400">
                <th class="p-2">Fecha y Hora</th>
                <th class="p-2">Acción</th>
                <th class="p-2">Detalles / Notas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory->movements as $movement)
                <tr class="border-b border-gray-200">
                    <td class="p-2 whitespace-nowrap">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td class="p-2 font-semibold">
                        @if($movement->type === 'assigned')
                            <span class="text-green-600">Asignado</span> a {{ $movement->toUser->name ?? 'Desconocido' }}
                        @elseif($movement->type === 'returned')
                            <span class="text-gray-600">Devuelto</span> por {{ $movement->fromUser->name ?? 'Desconocido' }}
                        @endif
                    </td>
                    <td class="p-2 text-gray-700">{{ $movement->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-500 italic">No hay registros en el historial de trazabilidad.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
