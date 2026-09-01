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
    @endphp

    <img src="{{ $logoSrc }}" class="watermark">

    <div class="mb-6 flex justify-between items-center no-print">
        <h1 class="text-2xl font-bold">Listado de Inventario</h1>
        <button onclick="window.print()" class="bg-amber-600 text-white px-4 py-2 rounded">Imprimir / Guardar PDF</button>
    </div>

    <h1 class="text-2xl font-bold mb-4 hidden print:block text-center">Inventario de la Banda</h1>

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
                    <td class="p-2">{{ $item->musician->name }} {{ $item->musician->lastname }}</td>
                    <td class="p-2">{{ $item->instrument->name }}</td>
                    <td class="p-2">
                        {{ $item->brand ? $item->brand->name : '' }} 
                        {{ $item->modelo }}
                    </td>
                    <td class="p-2">{{ $item->pivot->serial_number }}</td>
                    <td class="p-2">{{ ucfirst($item->pivot->propiedad) }}</td>
                    <td class="p-2">
                        @if($item->pivot->is_active)
                            <span class="text-green-600 font-bold">Activo</span>
                        @else
                            <span class="text-red-600 font-bold">Inactivo</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
