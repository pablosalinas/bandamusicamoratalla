<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justificante Parental - {{ $userName ?? 'Modelo' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 30px;
        }
        .content {
            margin-bottom: 50px;
            text-align: justify;
            position: relative;
            z-index: 10;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30%;
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .no-print {
            text-align: right;
            margin-bottom: 20px;
            z-index: 100;
            position: relative;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            margin-left: 10px;
        }
        .btn-close { background-color: #4b5563; }
        .btn-print { background-color: #d97706; }
        
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    @php
        // Logo para marca de agua
        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $logos = [];
        foreach ($rawLogos as $logo) {
            if (is_string($logo)) $logos[] = ['path' => $logo, 'order' => 999];
            else if (is_array($logo)) $logos[] = $logo;
        }
        usort($logos, function($a, $b) { return ($a['order'] ?? 999) <=> ($b['order'] ?? 999); });
        $logos = array_column($logos, 'path');
        $primaryLogo = count($logos) > 0 ? $logos[0] : 'images/logo.png';
        $logoSrc = str_starts_with($primaryLogo, 'images/') ? asset($primaryLogo) : asset('storage/' . $primaryLogo);
    @endphp

    {{-- Marca de agua --}}
    <img src="{{ $logoSrc }}" class="watermark">

    {{-- Botones (no aparecen en el PDF) --}}
    <div class="no-print">
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button onclick="window.close()" class="btn btn-close">Cerrar</button>
            <button onclick="window.print()" class="btn btn-print">Imprimir / Guardar PDF</button>
        </div>
    </div>

    {{-- Contenido del justificante --}}
    <div class="content">
        {!! $template !!}
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
