<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Partituras - PDF</title>
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
        <h1 class="text-2xl font-bold">Catálogo de Partituras</h1>
        <button onclick="window.print()" class="bg-amber-600 text-white px-4 py-2 rounded">Imprimir / Guardar PDF</button>
    </div>

    <h1 class="text-2xl font-bold mb-4 hidden print:block text-center">Archivo Musical de la Banda</h1>
    
    @if(request('work_type'))
    <p class="text-center mb-4 italic text-gray-700">Filtro: {{ request('work_type') }}</p>
    @endif

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="p-2">Obra (Título)</th>
                <th class="p-2">Compositor</th>
                <th class="p-2">Arreglista</th>
                <th class="p-2">Tipo</th>
                <th class="p-2">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sheetMusics as $sheet)
                <tr class="border-b">
                    <td class="p-2 font-medium">{{ $sheet->title }}</td>
                    <td class="p-2">{{ $sheet->composer ?? '-' }}</td>
                    <td class="p-2">{{ $sheet->arranger ?? '-' }}</td>
                    <td class="p-2">{{ $sheet->work_type ?? '-' }}</td>
                    <td class="p-2">
                        @if($sheet->is_active)
                            <span class="text-green-600 font-bold">Activa</span>
                        @else
                            <span class="text-red-600 font-bold">Inactiva</span>
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
