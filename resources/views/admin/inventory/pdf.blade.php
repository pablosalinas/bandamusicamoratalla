<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - PDF</title>
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
            @page { margin: 0; }
            .no-print { display: none !important; }
            body { padding: 1.5cm; }
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
        <div class="flex gap-2">
            <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cerrar y Regresar</button>
            <button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded">Imprimir / Guardar PDF</button>
        </div>
    </div>

    <div class="header">
        <img src="{{ $logoSrc }}" alt="Logo">
        <div class="header-text">
            <h2>{{ $bandName }}</h2>
            <h1>INVENTARIO DE LA BANDA</h1>
        </div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="p-2">Músico</th>
                <th class="p-2">Instrumento</th>
                <th class="p-2">Marca / Modelo</th>
                <th class="p-2">Nº Serie</th>
                <th class="p-2">Propiedad</th>
                <th class="p-2">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory as $item)
                <tr class="border-b">
                    <td class="p-2">
                        @if($item->users->count() > 0)
                            {{ $item->users->map(fn($u) => $u->name . ' ' . $u->last_name)->implode(', ') }}
                        @else
                            <span class="text-gray-500 italic">En stock</span>
                        @endif
                    </td>
                    <td class="p-2">{{ $item->instrument->name ?? 'Desconocido' }}</td>
                    <td class="p-2">
                        {{ $item->brand ? $item->brand->name : '' }} 
                        {{ $item->model }}
                    </td>
                    <td class="p-2">{{ $item->serial_number }}</td>
                    <td class="p-2">{{ ucfirst($item->propiedad) }}</td>
                    <td class="p-2">
                        @if($item->is_active)
                            <span class="text-green-600 font-bold">Activo</span>
                        @else
                            <span class="text-red-600 font-bold">Inactivo</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


</body>
</html>
