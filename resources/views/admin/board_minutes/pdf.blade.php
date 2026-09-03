<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta - {{ $minute->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 30px;
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
        .date {
            font-style: italic;
            color: #666;
            font-size: 14px;
            margin-top: 4px;
        }
        .content {
            margin-bottom: 50px;
            text-align: justify;
        }
        .signatures {
            margin-top: 80px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .no-print {
            text-align: right;
            margin-bottom: 20px;
        }
        .btn-print {
            background-color: #d97706;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
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
        // Logo para marca de agua
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

        // Nombre de la banda
        $bandName = \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música');

        // Meses en español
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];

        // Fecha de la junta en español
        $dateObj = $minute->date instanceof \Carbon\Carbon ? $minute->date : \Carbon\Carbon::parse($minute->date);
        $fechaJuntaEsp = $dateObj->day . ' de ' . $meses[(int)$dateObj->format('n')] . ' de ' . $dateObj->year;

        // Fecha de impresión en español
        $hoy = now();
        $fechaImpresionEsp = $hoy->day . ' de ' . $meses[(int)$hoy->format('n')] . ' de ' . $hoy->year;
    @endphp

    {{-- Marca de agua --}}
    <img src="{{ $logoSrc }}" class="watermark">

    {{-- Botón de impresión (no aparece en el PDF) --}}
    <div class="no-print">
        <div class="flex gap-2"><button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cerrar y Regresar</button><button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded">Imprimir / Guardar PDF</button></div>
    </div>

    {{-- Cabecera con título y fecha de junta --}}
    <div class="header">
        <img src="{{ $logoSrc }}" alt="Logo">
        <div class="header-text">
            <h2>{{ $bandName }}</h2>
            <h1>ACTA DE JUNTA: {{ mb_strtoupper($minute->title) }}</h1>
            <div class="date">Fecha de la junta: {{ $fechaJuntaEsp }}</div>
        </div>
    </div>

    {{-- Contenido del acta --}}
    <div class="content">
        {!! $minute->content !!}
    </div>

    {{-- Espacio para firmas --}}
    <div class="signatures">
        <div class="signature-box" style="float: left;">
            <div class="signature-line">
                Fdo: Presidente/a
            </div>
        </div>
        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                Fdo: Secretario/a
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>


</body>
</html>
