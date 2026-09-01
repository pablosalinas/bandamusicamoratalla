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
        .instruments-list {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px dashed #ccc;
        }
        .instrument-badge {
            display: inline-block;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 1px 6px;
            font-size: 0.72rem;
            margin: 1px 2px;
            white-space: nowrap;
        }
        .instrument-badge .tipo {
            color: #6b7280;
            font-style: italic;
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
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 text-sm font-medium cursor-pointer select-none">
                <input type="checkbox" id="toggle-desglose"
                       {{ $desglose ? 'checked' : '' }}
                       onchange="toggleDesglose(this)"
                       class="h-4 w-4 rounded border-gray-400 accent-amber-600">
                <span>Desglose por instrumento</span>
            </label>
            <button onclick="window.print()" class="bg-amber-600 text-white px-4 py-2 rounded">Imprimir / Guardar PDF</button>
        </div>
    </div>

    <h1 class="text-2xl font-bold mb-4 hidden print:block text-center">Archivo Musical de la Banda</h1>
    
    @if(request('work_type') || isset($instrument))
    <p class="text-center mb-4 italic text-gray-700">
        Filtros aplicados: 
        {{ request('work_type') ? 'Tipo: ' . request('work_type') : '' }}
        {{ request('work_type') && isset($instrument) ? ' | ' : '' }}
        {{ isset($instrument) ? 'Instrumento: ' . $instrument->name : '' }}
        @if($desglose)
            {{ (request('work_type') || isset($instrument)) ? ' | ' : '' }}<strong>Desglose por instrumento activado</strong>
        @endif
    </p>
    @elseif($desglose)
    <p class="text-center mb-4 italic text-gray-700"><strong>Desglose por instrumento activado</strong></p>
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
                    <td class="p-2 font-medium align-top">
                        {{ $sheet->title }}
                        @if($desglose)
                            @php
                                $instrumentsWithFile = $sheet->instruments->filter(fn($i) => !empty($i->pivot->pdf_file_path));
                            @endphp
                            @if($instrumentsWithFile->isNotEmpty())
                                <div class="instruments-list">
                                    @foreach($instrumentsWithFile as $instr)
                                        <span class="instrument-badge">
                                            {{ $instr->name }}
                                            @if($instr->pivot->tipo_partitura)
                                                <span class="tipo">({{ $instr->pivot->tipo_partitura }})</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="instruments-list text-xs text-gray-400 italic">Sin partituras individuales subidas</div>
                            @endif
                        @endif
                    </td>
                    <td class="p-2 align-top">{{ $sheet->composer ?? '-' }}</td>
                    <td class="p-2 align-top">{{ $sheet->arranger ?? '-' }}</td>
                    <td class="p-2 align-top">{{ $sheet->work_type ?? '-' }}</td>
                    <td class="p-2 align-top">
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
        function toggleDesglose(checkbox) {
            const url = new URL(window.location.href);
            if (checkbox.checked) {
                url.searchParams.set('desglose', '1');
            } else {
                url.searchParams.delete('desglose');
            }
            window.location.href = url.toString();
        }

        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
