<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatutos - {{ $bandName ?? 'Banda de Música' }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white;
                color: black;
                font-size: 11pt;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
            @page {
                margin: 0;
            }
            body {
                padding: 1.5cm !important;
            }
            .bg-gray-100 { 
                background-color: #ffffff !important; 
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
        @media print {
            .watermark {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        .prose img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased p-8">
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

    <!-- Barra de acciones (no se imprime) -->
    <div class="max-w-5xl mx-auto mb-8 flex justify-between items-center no-print">
        <a href="{{ route('estatutos') }}" class="text-gray-600 hover:text-gray-900 font-semibold flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
        <button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Informe
        </button>
    </div>

    <!-- Hoja del Informe -->
    <div class="max-w-5xl mx-auto bg-white p-10 shadow-lg rounded-lg border border-gray-200">
        
        <!-- Cabecera del Informe -->
        <div class="flex flex-col items-center justify-center text-center mb-8 border-b-2 border-amber-600 pb-5">
            <img src="{{ $logoSrc }}" alt="Logo" class="w-20 object-contain mb-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $bandName }}</h2>
                <h1 class="text-2xl font-extrabold text-amber-600 mt-1 uppercase tracking-wider">ESTATUTOS</h1>
                <p class="text-sm text-gray-500 mt-2">Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</p>
            </div>
        </div>

        <!-- Contenido -->
        <div class="prose max-w-none text-gray-800 leading-relaxed">
            @if(empty($globalStatutes))
                <p class="text-gray-400 italic text-center">Los estatutos aún no han sido publicados.</p>
            @else
                {!! $globalStatutes !!}
            @endif
        </div>

        <div class="mt-16 text-center text-sm text-gray-500 italic avoid-break">
            <p>Documento oficial generado por la plataforma de gestión de la {{ $bandName }}.</p>
        </div>

    </div>
</body>
</html>
