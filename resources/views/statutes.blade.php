<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estatutos - {{ $globalBandName }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-950 text-gray-200">
    <nav class="bg-gray-950/90 backdrop-blur-md shadow-lg border-b border-gray-800 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="text-amber-500 hover:text-amber-400 font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al inicio
                </a>
                <span class="text-xl font-bold text-white">{{ $globalBandName }}</span>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto pt-32 pb-16 px-6">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 pb-6 border-b border-gray-800">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">Estatutos de la Banda</h1>
                <a href="{{ route('estatutos.pdf') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-900 bg-amber-500 hover:bg-amber-400">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Descargar PDF / Imprimir
                </a>
            </div>
            
            <div class="prose prose-invert prose-amber max-w-none text-gray-300 leading-relaxed">
                @if(empty($globalStatutes))
                    <p class="text-gray-400 italic">Los estatutos aún no han sido publicados.</p>
                @else
                    {!! $globalStatutes !!}
                @endif
            </div>
        </div>
    </div>
</body>
</html>
