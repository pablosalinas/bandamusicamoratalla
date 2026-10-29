<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buscador Global y Externo - {{ $globalBandName }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="antialiased bg-gray-950 text-gray-200 min-h-screen flex flex-col selection:bg-amber-500 selection:text-black">
    <!-- Navbar -->
    <nav class="bg-gray-950/90 backdrop-blur-md shadow-lg border-b border-gray-800 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="cursor-pointer shrink-0">
                        <x-logo-rotator class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden shadow-[0_0_15px_rgba(245,158,11,0.3)]" />
                    </a>
                    <div class="flex flex-col">
                        <a href="{{ url('/') }}" class="text-lg sm:text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 hover:opacity-90 transition-opacity">
                            {{ $globalBandName }}
                        </a>
                        <span class="text-xs text-gray-400 font-medium tracking-wide">Hemeroteca Histórica · Buscador</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <a href="{{ route('hemeroteca.news') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-900 text-gray-300 border border-gray-700 hover:border-amber-500/50 hover:text-amber-400 transition-all">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>Hemeroteca Noticias</span>
                    </a>
                    <a href="{{ route('hemeroteca.media') }}" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-900 text-gray-300 border border-gray-700 hover:border-amber-500/50 hover:text-amber-400 transition-all">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <span>Multimedia</span>
                    </a>

                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-gray-400 hover:text-amber-400 transition-colors ml-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden sm:inline">Volver a Inicio</span>
                        <span class="sm:hidden">Inicio</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 pt-28 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full"
          x-data="{
              scope: '{{ $scope }}',
              category: '{{ $category }}',
              datePreset: '{{ $datePreset }}',
              bandContext: {{ $bandContext ? 'true' : 'false' }},
              showDateFilters: {{ ($year || $fromDate || $toDate || $datePreset !== 'all') ? 'true' : 'false' }},
              openHistoryModal: false,
              photoLightboxOpen: false,
              activePhotoIndex: 0,
              photoList: {{ json_encode($photoResults->values()) }}
          }">

        <!-- Breadcrumb & Header de la Zona de Hemeroteca -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                <a href="{{ url('/') }}" class="hover:text-amber-400 transition-colors">Inicio</a>
                <span>/</span>
                <a href="{{ route('hemeroteca.news') }}" class="hover:text-amber-400 transition-colors">Hemeroteca</a>
                <span>/</span>
                <span class="text-amber-500 font-medium">Buscador</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight flex items-center gap-3">
                        <span class="p-2.5 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-400 shadow-lg shadow-amber-500/10">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <span>Hemeroteca · Buscador</span>
                    </h1>
                    <p class="text-gray-400 mt-1.5 text-sm sm:text-base">
                        Encuentra noticias, eventos, música, fotos e historia dentro de nuestra web o en toda Internet.
                    </p>
                </div>

                <!-- Pestañas de Hemeroteca Destacadas -->
                <div class="inline-flex flex-wrap rounded-xl p-1.5 bg-gray-900/90 border border-amber-500/30 shadow-[0_0_20px_rgba(245,158,11,0.15)] self-start md:self-auto gap-1">
                    <a href="{{ route('hemeroteca.news') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-400 hover:text-amber-400 hover:bg-gray-800/80 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>Noticias y Eventos</span>
                    </a>
                    <a href="{{ route('hemeroteca.media') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-400 hover:text-amber-400 hover:bg-gray-800/80 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <span>Multimedia</span>
                    </a>
                    <span class="px-4 py-2 rounded-lg text-xs font-extrabold bg-gradient-to-r from-amber-500 to-amber-600 text-black shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Buscador Web / Internet</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulario Principal de Búsqueda (Amigable y de Alta Visibilidad) -->
        <div class="glass-panel p-5 sm:p-8 rounded-3xl mb-10 shadow-2xl border-2 border-amber-500/30 bg-gradient-to-b from-gray-900/95 to-gray-950/95">
            <form method="GET" action="{{ route('search.index') }}" id="global-search-form" class="space-y-6">
                <input type="hidden" name="scope" :value="scope">
                <input type="hidden" name="category" :value="category">
                <input type="hidden" name="band_context" :value="bandContext ? '1' : '0'">

                <!-- PROTAGONISTA: Campo de Búsqueda Grande y Muy Visible -->
                <div>
                    <label for="q" class="block text-sm sm:text-base font-bold text-amber-400 mb-2.5 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>¿Qué estás buscando?</span>
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 sm:pl-5">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="q" id="q" value="{{ $q }}" autofocus
                                   placeholder="Escribe aquí lo que buscas: Santa Cecilia, pasodoble, concierto, director..."
                                   class="w-full rounded-2xl bg-white text-gray-950 font-semibold placeholder-gray-400 border-2 border-amber-400 py-4 pl-12 sm:pl-14 pr-12 text-base sm:text-lg shadow-[0_0_25px_rgba(245,158,11,0.2)] focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-500/30 transition-all">
                            @if($q !== '')
                                <a href="{{ route('search.index', ['scope' => $scope, 'category' => $category]) }}"
                                   class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-700 transition-colors"
                                   title="Borrar texto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 py-4 px-8 text-base sm:text-lg font-extrabold text-black transition-all shadow-xl shadow-amber-500/25 hover:scale-[1.02] active:scale-[0.99] flex items-center justify-center gap-2.5 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Buscar ahora</span>
                        </button>
                    </div>
                </div>

                <!-- Opciones Amigables en Tarjetas Claras -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 pt-2 border-t border-gray-800/80">
                    <!-- 1. ¿Dónde buscar? -->
                    <div class="lg:col-span-5 bg-gray-900/70 border border-gray-800 rounded-2xl p-4">
                        <span class="block text-xs font-bold uppercase tracking-wider text-amber-400 mb-2.5">1. ¿Dónde quieres buscar?</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <button type="button"
                                    @click="scope = 'web'"
                                    :class="scope === 'web' ? 'bg-amber-500 text-black font-extrabold border-amber-400 shadow-md' : 'bg-gray-950 text-gray-300 border-gray-800 hover:border-amber-500/40 hover:text-amber-300 font-semibold'"
                                    class="px-3 py-2.5 rounded-xl text-xs sm:text-sm border transition-all flex items-center justify-center gap-1.5 text-center">
                                <span>🏠 En la Web</span>
                            </button>

                            <button type="button"
                                    @click="scope = 'internet'"
                                    :class="scope === 'internet' ? 'bg-blue-600 text-white font-extrabold border-blue-400 shadow-md' : 'bg-gray-950 text-gray-300 border-gray-800 hover:border-blue-500/40 hover:text-blue-300 font-semibold'"
                                    class="px-3 py-2.5 rounded-xl text-xs sm:text-sm border transition-all flex items-center justify-center gap-1.5 text-center">
                                <span>🌍 En Internet</span>
                            </button>

                            <button type="button"
                                    @click="scope = 'both'"
                                    :class="scope === 'both' ? 'bg-gradient-to-r from-amber-500 to-blue-600 text-white font-extrabold border-amber-400 shadow-md' : 'bg-gray-950 text-gray-300 border-gray-800 hover:border-amber-500/40 hover:text-amber-300 font-semibold'"
                                    class="px-3 py-2.5 rounded-xl text-xs sm:text-sm border transition-all flex items-center justify-center gap-1.5 text-center">
                                <span>⚡ En Ambos</span>
                            </button>
                        </div>

                        <!-- Opción contextual cuando se busca en Internet -->
                        <div x-show="scope === 'internet' || scope === 'both'" x-transition class="mt-3 pt-3 border-t border-gray-800 flex items-start gap-2.5">
                            <input type="checkbox" id="band_context_toggle" x-model="bandContext" class="mt-0.5 rounded bg-gray-900 border-gray-600 text-amber-500 focus:ring-amber-500 w-4 h-4 cursor-pointer">
                            <label for="band_context_toggle" class="text-xs text-gray-300 cursor-pointer select-none leading-snug">
                                <span class="font-bold text-blue-300">Relacionado con "{{ $globalBandName }}"</span>
                                <span class="block text-[11px] text-gray-400">Desmárcalo para buscar cualquier tema libre en Internet.</span>
                            </label>
                        </div>
                    </div>

                    <!-- 2. ¿En qué apartado de la web? -->
                    <div class="lg:col-span-7 bg-gray-900/70 border border-gray-800 rounded-2xl p-4" x-show="scope === 'web' || scope === 'both'" x-transition>
                        <span class="block text-xs font-bold uppercase tracking-wider text-amber-400 mb-2.5">2. ¿Qué apartado de la web quieres ver?</span>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $categoriesList = [
                                    'all' => ['label' => '✨ Todo', 'desc' => 'Todas las secciones'],
                                    'news' => ['label' => '📰 Noticias', 'desc' => 'Publicaciones'],
                                    'events' => ['label' => '🎫 Eventos', 'desc' => 'Conciertos y actos'],
                                    'music' => ['label' => '🎵 Música', 'desc' => 'Audios y vídeos'],
                                    'photos' => ['label' => '📸 Fotos', 'desc' => 'Galerías'],
                                    'history' => ['label' => '📜 Historia', 'desc' => 'Historia de la banda'],
                                ];
                            @endphp
                            @foreach($categoriesList as $catKey => $catInfo)
                                <button type="button"
                                        @click="category = '{{ $catKey }}'"
                                        :class="category === '{{ $catKey }}' ? 'bg-amber-500 text-black font-extrabold border-amber-400 shadow-md' : 'bg-gray-950 text-gray-300 border-gray-800 hover:border-gray-700 hover:text-white font-semibold'"
                                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm border transition-all flex items-center gap-1.5">
                                    <span>{{ $catInfo['label'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 3. Filtro de Fechas (Amigable y por defecto lo coge todo) -->
                <div class="bg-gray-900/70 border border-gray-800 rounded-2xl p-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-400 mr-1">3. Fechas:</span>

                            <button type="button"
                                    @click="datePreset = 'all'; showDateFilters = false; document.getElementById('from_date').value = ''; document.getElementById('to_date').value = ''; document.getElementById('year').value = '';"
                                    :class="datePreset === 'all' && !showDateFilters ? 'bg-emerald-500 text-black font-extrabold border-emerald-400 shadow' : 'bg-gray-950 text-gray-300 border-gray-800 hover:text-white'"
                                    class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm border transition-all flex items-center gap-1.5">
                                <span>📅 Todas las fechas (Por defecto)</span>
                            </button>

                            <button type="button"
                                    @click="datePreset = 'month'; showDateFilters = false; document.getElementById('from_date').value = ''; document.getElementById('to_date').value = ''; document.getElementById('year').value = '';"
                                    :class="datePreset === 'month' ? 'bg-amber-500 text-black font-extrabold border-amber-400 shadow' : 'bg-gray-950 text-gray-300 border-gray-800 hover:text-white'"
                                    class="px-3 py-1.5 rounded-xl text-xs sm:text-sm border transition-all">
                                Último mes
                            </button>

                            <button type="button"
                                    @click="datePreset = 'year'; showDateFilters = false; document.getElementById('from_date').value = ''; document.getElementById('to_date').value = ''; document.getElementById('year').value = '';"
                                    :class="datePreset === 'year' ? 'bg-amber-500 text-black font-extrabold border-amber-400 shadow' : 'bg-gray-950 text-gray-300 border-gray-800 hover:text-white'"
                                    class="px-3 py-1.5 rounded-xl text-xs sm:text-sm border transition-all">
                                Último año
                            </button>

                            <button type="button"
                                    @click="showDateFilters = !showDateFilters"
                                    :class="showDateFilters ? 'bg-amber-500/20 text-amber-300 border-amber-500/50 font-bold' : 'bg-gray-950 text-gray-300 border-gray-800 hover:text-amber-400'"
                                    class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm border transition-all flex items-center gap-1.5">
                                <span>🔍 Filtrar por Año o Fechas concretas</span>
                                <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': showDateFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>

                        @if($q !== '' || $scope !== 'web' || $category !== 'all' || $year || $fromDate || $toDate || $datePreset !== 'all')
                            <a href="{{ route('search.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-400 hover:text-rose-300 bg-rose-500/10 border border-rose-500/20 px-3 py-1.5 rounded-xl transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Limpiar búsqueda</span>
                            </a>
                        @endif
                    </div>

                    <input type="hidden" name="date_preset" :value="datePreset">

                    <!-- Desplegable suave para Año / Desde / Hasta -->
                    <div x-show="showDateFilters" x-transition class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-800">
                        <div>
                            <label for="year" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Año concreto</label>
                            <select name="year" id="year"
                                    @change="if($el.value !== '') datePreset = 'custom'"
                                    class="w-full rounded-xl bg-gray-950 border-2 border-gray-700 py-2.5 px-3 text-sm text-white font-semibold focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                <option value="">Todos los años</option>
                                @foreach($availableYears as $yr)
                                    <option value="{{ $yr }}" {{ (string)$year === (string)$yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="from_date" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Desde la fecha</label>
                            <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}"
                                   @change="if($el.value !== '') datePreset = 'custom'"
                                   class="w-full rounded-xl bg-gray-950 border-2 border-gray-700 py-2.5 px-3 text-sm text-white font-semibold focus:border-amber-500 focus:ring-1 focus:ring-amber-500" style="color-scheme: dark;">
                        </div>

                        <div>
                            <label for="to_date" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Hasta la fecha</label>
                            <input type="date" name="to_date" id="to_date" value="{{ $toDate }}"
                                   @change="if($el.value !== '') datePreset = 'custom'"
                                   class="w-full rounded-xl bg-gray-950 border-2 border-gray-700 py-2.5 px-3 text-sm text-white font-semibold focus:border-amber-500 focus:ring-1 focus:ring-amber-500" style="color-scheme: dark;">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Barra Resumen de Resultados y Saltos Rápidos -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-4 border-b border-gray-800">
            <div class="flex flex-wrap items-center gap-2 text-sm">
                <span class="text-gray-400">Mostrando resultados</span>
                @if($q !== '')
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-300 font-bold">"{{ $q }}"</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full bg-gray-800 text-gray-300 font-medium">Todo el catálogo</span>
                @endif

                <span class="text-gray-600">•</span>
                @if(!$year && !$fromDate && !$toDate)
                    <span class="text-emerald-400 text-xs font-semibold flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Todas las fechas incluidas
                    </span>
                @else
                    <span class="text-amber-400 text-xs font-semibold bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 rounded-full">
                        Filtro de fechas activo
                        @if($year) (Año {{ $year }}) @endif
                        @if($fromDate) (Desde {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }}) @endif
                        @if($toDate) (Hasta {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}) @endif
                    </span>
                @endif
            </div>

            <!-- Anclas rápidas a cada bloque -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                @if(in_array($scope, ['web', 'both']))
                    @if(in_array($category, ['all', 'news']))
                        <a href="#resultados-noticias" class="px-3 py-1 rounded-full bg-gray-900 border border-gray-800 hover:border-amber-500/50 text-gray-300 hover:text-amber-400 transition-colors">
                            Noticias ({{ $newsResults->count() }})
                        </a>
                    @endif
                    @if(in_array($category, ['all', 'events']))
                        <a href="#resultados-eventos" class="px-3 py-1 rounded-full bg-gray-900 border border-gray-800 hover:border-amber-500/50 text-gray-300 hover:text-amber-400 transition-colors">
                            Eventos ({{ $eventResults->count() }})
                        </a>
                    @endif
                    @if(in_array($category, ['all', 'music']))
                        <a href="#resultados-musica" class="px-3 py-1 rounded-full bg-gray-900 border border-gray-800 hover:border-amber-500/50 text-gray-300 hover:text-amber-400 transition-colors">
                            Música ({{ $musicResults->count() }})
                        </a>
                    @endif
                    @if(in_array($category, ['all', 'photos']))
                        <a href="#resultados-fotos" class="px-3 py-1 rounded-full bg-gray-900 border border-gray-800 hover:border-amber-500/50 text-gray-300 hover:text-amber-400 transition-colors">
                            Fotos ({{ $photoResults->count() }})
                        </a>
                    @endif
                    @if(in_array($category, ['all', 'history']))
                        <a href="#resultados-historia" class="px-3 py-1 rounded-full bg-gray-900 border border-gray-800 hover:border-amber-500/50 text-gray-300 hover:text-amber-400 transition-colors">
                            Historia ({{ $historyResults['matched'] ? 'Sí' : '0' }})
                        </a>
                    @endif
                @endif
                @if(in_array($scope, ['internet', 'both']))
                    <a href="#resultados-internet" class="px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/40 text-blue-300 hover:bg-blue-500/30 font-semibold transition-colors">
                        🌍 Internet ({{ $externalResults->count() }})
                    </a>
                @else
                    <a href="{{ route('search.index', ['q' => $q, 'scope' => 'both', 'category' => $category, 'year' => $year, 'from_date' => $fromDate, 'to_date' => $toDate]) }}"
                       class="px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 hover:bg-blue-500/20 font-semibold transition-colors flex items-center gap-1">
                        <span>🌍 Buscar también en toda Internet</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- BLOQUE EXTERNO: BÚSQUEDA EN TODA INTERNET (SI SE SELECCIONA) -->
        <!-- ========================================================= -->
        @if(in_array($scope, ['internet', 'both']))
            <section id="resultados-internet" class="mb-14 scroll-mt-28">
                <div class="glass-panel rounded-2xl p-6 border border-blue-500/30 shadow-[0_0_30px_rgba(59,130,246,0.1)]">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 mb-6 border-b border-gray-800">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/40 text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <span>Búsqueda Externa en Toda Internet</span>
                            </div>
                            <h2 class="text-2xl font-extrabold text-white">
                                Resultados en Internet para: <span class="text-blue-400">"{{ $externalEffectiveQuery }}"</span>
                            </h2>
                            <p class="text-xs text-gray-400 mt-1">
                                Noticias, prensa, vídeos y páginas externas en la red (con tus filtros de fecha aplicados).
                            </p>
                        </div>

                        <!-- Accesos directos a buscadores externos con los filtros aplicados -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ $externalSearchLinks['google_web'] }}" target="_blank" rel="noopener"
                               class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Abrir en Google</span>
                                <svg class="w-3 h-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="{{ $externalSearchLinks['google_news'] }}" target="_blank" rel="noopener"
                               class="px-3.5 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-gray-200 border border-gray-700 hover:border-blue-400 text-xs font-semibold transition-all flex items-center gap-1.5">
                                <span>📰 Google Noticias</span>
                                <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="{{ $externalSearchLinks['youtube'] }}" target="_blank" rel="noopener"
                               class="px-3.5 py-2 rounded-xl bg-red-600/20 hover:bg-red-600/30 text-red-300 border border-red-500/30 text-xs font-semibold transition-all flex items-center gap-1.5">
                                <span>▶ YouTube</span>
                                <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="{{ $externalSearchLinks['google_images'] }}" target="_blank" rel="noopener"
                               class="px-3.5 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-gray-200 border border-gray-700 hover:border-amber-400 text-xs font-semibold transition-all flex items-center gap-1.5">
                                <span>🖼️ Imágenes en Internet</span>
                                <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    @if($externalResults->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($externalResults as $ext)
                                <a href="{{ $ext['url'] }}" target="_blank" rel="noopener noreferrer"
                                   class="bg-gray-900/90 hover:bg-gray-900 border border-gray-800 hover:border-blue-500/50 rounded-xl p-5 flex flex-col justify-between transition-all hover:-translate-y-1 shadow-lg group">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 text-xs mb-2.5">
                                            <span class="px-2.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 font-semibold truncate max-w-[65%]">
                                                {{ $ext['source'] }}
                                            </span>
                                            @if($ext['date'])
                                                <span class="text-gray-400 shrink-0">{{ $ext['date']->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                        <h3 class="text-base font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-3 mb-2">
                                            {{ $ext['title'] }}
                                        </h3>
                                        @if(!empty($ext['snippet']))
                                            <p class="text-xs text-gray-400 line-clamp-3 leading-relaxed">
                                                {{ $ext['snippet'] }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-gray-800/80 flex items-center justify-between text-xs font-semibold text-blue-400 group-hover:text-blue-300">
                                        <span>Abrir noticia externa</span>
                                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-950/60 rounded-xl border border-gray-800 p-6">
                            <p class="text-gray-300 font-semibold mb-2">No se encontraron artículos directos en el feed RSS para ese filtro exacto o deseas explorar toda la red.</p>
                            <p class="text-xs text-gray-400 mb-5">Puedes abrir directamente los resultados completos en los buscadores de Internet con tus filtros de fecha ya aplicados:</p>
                            <div class="flex flex-wrap justify-center gap-3">
                                <a href="{{ $externalSearchLinks['google_web'] }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition-all shadow-lg">
                                    Buscar "{{ $externalEffectiveQuery }}" en Google Web
                                </a>
                                <a href="{{ $externalSearchLinks['google_news'] }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-white text-sm font-semibold transition-all border border-gray-700">
                                    Buscar en Google Noticias
                                </a>
                                <a href="{{ $externalSearchLinks['youtube'] }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl bg-red-600/20 hover:bg-red-600/30 text-red-300 text-sm font-semibold transition-all border border-red-500/30">
                                    Buscar vídeos en YouTube
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if(in_array($scope, ['web', 'both']))

            @if($totalWebResults === 0)
                <div class="glass-panel rounded-2xl p-10 text-center my-8 border border-gray-800">
                    <svg class="w-14 h-14 text-amber-500/60 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">No se han encontrado resultados internos en la web con esos filtros</h3>
                    <p class="text-gray-400 text-sm max-w-xl mx-auto mb-6">
                        Prueba con otras palabras clave, selecciona "Todas las fechas" o busca directamente este término en toda Internet.
                    </p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('search.index', ['q' => $q, 'scope' => 'internet', 'year' => $year, 'from_date' => $fromDate, 'to_date' => $toDate]) }}"
                           class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition-all flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            <span>Buscar "{{ $q ?: $globalBandName }}" en toda Internet</span>
                        </a>
                        <a href="{{ route('search.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200 text-sm font-semibold transition-colors">
                            Ver todo el contenido de la web
                        </a>
                    </div>
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- 1. SECCIÓN NOTICIAS -->
            <!-- ========================================================= -->
            @if(in_array($category, ['all', 'news']) && $newsResults->count() > 0)
                <section id="resultados-noticias" class="mb-14 scroll-mt-28">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Noticias ({{ $newsResults->count() }})</h2>
                                <p class="text-xs text-gray-400">Publicaciones, crónicas y novedades de la banda</p>
                            </div>
                        </div>
                        <a href="{{ route('hemeroteca.news', ['q' => $q, 'year' => $year, 'from_date' => $fromDate, 'to_date' => $toDate]) }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">
                            <span>Ver en Hemeroteca de Noticias</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($newsResults as $item)
                            <div x-data="{
                                    openNews: false,
                                    activeNewsSlide: 0,
                                    newsSlides: {{ json_encode($item->newsImages->map(function($i) {
                                        $ext = strtolower(pathinfo($i->url, PATHINFO_EXTENSION));
                                        $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                                        return ['url' => $i->url, 'desc' => $i->description, 'author' => $i->author, 'type' => $isVideo ? 'video' : 'image'];
                                    })) }}
                                 }"
                                 class="glass-panel rounded-2xl overflow-hidden hover:shadow-[0_0_25px_rgba(245,158,11,0.15)] transition-all duration-300 hover:-translate-y-1 flex flex-col cursor-pointer group"
                                 @click="openNews = true">

                                @if($item->newsImages->count() > 0)
                                    @php
                                        $firstMedia = $item->newsImages->first();
                                        $firstExt = strtolower(pathinfo($firstMedia->url, PATHINFO_EXTENSION));
                                        $firstIsVideo = in_array($firstExt, ['mp4', 'mov', 'webm', 'avi']);
                                    @endphp
                                    <div class="h-48 w-full overflow-hidden relative bg-gray-900">
                                        @if($firstIsVideo)
                                            <video src="{{ $firstMedia->url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" muted playsinline></video>
                                        @else
                                            <img src="{{ $firstMedia->url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                        @if($item->newsImages->count() > 1)
                                            <div class="absolute top-2.5 left-2.5 bg-black/70 backdrop-blur-md text-amber-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-amber-500/30">
                                                {{ $item->newsImages->count() }} archivos
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="flex items-center justify-between text-xs text-amber-500 font-semibold mb-2">
                                        <span>Noticia</span>
                                        <span>{{ ($item->event_date ?: $item->created_at)->format('d/m/Y') }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-amber-400 transition-colors">{{ $item->title }}</h3>
                                    <p class="text-sm text-gray-400 line-clamp-3 mb-4 flex-1">
                                        {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($item->content), ENT_QUOTES, 'UTF-8'), 140) }}
                                    </p>
                                    <div class="mt-auto pt-3 border-t border-gray-800 flex items-center justify-between text-xs font-semibold text-amber-400">
                                        <span>Leer noticia completa</span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                </div>

                                <!-- Modal Noticia -->
                                <template x-teleport="body">
                                    <div x-show="openNews" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
                                        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openNews = false"></div>
                                        <div class="relative bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden" @keydown.escape.window="openNews = false">
                                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                                                <h3 class="text-xl font-bold text-white truncate pr-4">{{ $item->title }}</h3>
                                                <button @click="openNews = false" class="text-gray-400 hover:text-white bg-gray-800 p-2 rounded-full hover:bg-gray-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                            <div class="p-6 overflow-y-auto">
                                                @if($item->newsImages->count() > 0)
                                                    <div class="relative rounded-xl overflow-hidden bg-black mb-6 aspect-video flex items-center justify-center">
                                                        <template x-for="(slide, idx) in newsSlides" :key="idx">
                                                            <div x-show="activeNewsSlide === idx" class="absolute inset-0 flex items-center justify-center">
                                                                <template x-if="slide.type === 'video'">
                                                                    <video :src="slide.url" class="max-w-full max-h-full object-contain" controls></video>
                                                                </template>
                                                                <template x-if="slide.type !== 'video'">
                                                                    <img :src="slide.url" class="max-w-full max-h-full object-contain">
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <button x-show="newsSlides.length > 1" @click.stop="activeNewsSlide = (activeNewsSlide - 1 + newsSlides.length) % newsSlides.length" class="absolute left-3 bg-black/60 hover:bg-amber-600 text-white p-2 rounded-full">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                        </button>
                                                        <button x-show="newsSlides.length > 1" @click.stop="activeNewsSlide = (activeNewsSlide + 1) % newsSlides.length" class="absolute right-3 bg-black/60 hover:bg-amber-600 text-white p-2 rounded-full">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        </button>
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-4 text-sm text-gray-400 mb-4 border-b border-gray-800 pb-3">
                                                    <span>📅 Publicado: {{ $item->created_at->format('d/m/Y') }}</span>
                                                    @if($item->event_date)
                                                        <span class="text-amber-400 font-semibold">🎫 Fecha Evento: {{ $item->event_date->format('d/m/Y') }}</span>
                                                    @endif
                                                </div>
                                                <div class="prose prose-invert prose-amber max-w-none">
                                                    {!! $item->content !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- ========================================================= -->
            <!-- 2. SECCIÓN EVENTOS Y CONCIERTOS -->
            <!-- ========================================================= -->
            @if(in_array($category, ['all', 'events']) && $eventResults->count() > 0)
                <section id="resultados-eventos" class="mb-14 scroll-mt-28">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Eventos y Actuaciones ({{ $eventResults->count() }})</h2>
                                <p class="text-xs text-gray-400">Conciertos, procesiones, certámenes y salidas de la banda</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($eventResults as $ev)
                            <div class="glass-panel rounded-2xl p-5 border border-gray-800 hover:border-purple-500/40 transition-all flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-500/15 text-purple-300 border border-purple-500/30">
                                            {{ $ev['type'] }}
                                        </span>
                                        @if($ev['event_date'])
                                            <span class="text-xs font-bold text-amber-400 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ \Carbon\Carbon::parse($ev['event_date'])->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-white mb-2">{{ $ev['title'] }}</h3>
                                    @if(!empty($ev['description']))
                                        <p class="text-xs text-gray-400 line-clamp-3 leading-relaxed mb-4">
                                            {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($ev['description']), ENT_QUOTES, 'UTF-8'), 160) }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-800/80 flex items-center justify-between">
                                    <a href="{{ route('hemeroteca.news', ['q' => $ev['title']]) }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">
                                        <span>Ver crónica / detalles</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- ========================================================= -->
            <!-- 3. SECCIÓN MÚSICA Y ARCHIVO MULTIMEDIA -->
            <!-- ========================================================= -->
            @if(in_array($category, ['all', 'music']) && $musicResults->count() > 0)
                <section id="resultados-musica" class="mb-14 scroll-mt-28">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Música y Archivo Sonoro ({{ $musicResults->count() }})</h2>
                                <p class="text-xs text-gray-400">Audios, vídeos, interpretaciones y compositores</p>
                            </div>
                        </div>
                        <a href="{{ route('hemeroteca.media', ['title' => $q, 'year' => $year, 'from_date' => $fromDate, 'to_date' => $toDate]) }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">
                            <span>Ir a Hemeroteca Multimedia</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($musicResults as $media)
                            <div class="glass-panel rounded-2xl overflow-hidden flex flex-col border border-gray-800 hover:border-amber-500/30 transition-all">
                                <div class="relative aspect-video bg-black/70 flex items-center justify-center">
                                    @if($media->type === 'video')
                                        <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover" controls preload="metadata" controlsList="nodownload"></video>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-900/40 to-gray-950 p-4">
                                            <svg class="w-12 h-12 text-amber-500/80 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                            <audio src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-10" controls preload="metadata" controlsList="nodownload"></audio>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5 flex-1 flex flex-col">
                                    <h3 class="text-lg font-bold text-white mb-2">{{ $media->title }}</h3>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($media->music_type)
                                            <span class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-xs font-medium">{{ $media->music_type }}</span>
                                        @endif
                                        @if($media->composer)
                                            <span class="px-2 py-0.5 rounded bg-gray-800 text-gray-300 text-xs font-medium">🎼 {{ $media->composer }}</span>
                                        @endif
                                        @if($media->performance_date)
                                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 text-xs font-medium">📅 {{ $media->performance_date->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                    @if($media->description)
                                        <p class="text-xs text-gray-400 line-clamp-2 mt-auto">{{ $media->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- ========================================================= -->
            <!-- 4. SECCIÓN FOTOS Y GALERÍAS -->
            <!-- ========================================================= -->
            @if(in_array($category, ['all', 'photos']) && $photoResults->count() > 0)
                <section id="resultados-fotos" class="mb-14 scroll-mt-28">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-pink-500/10 border border-pink-500/30 text-pink-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Fotos y Galerías ({{ $photoResults->count() }})</h2>
                                <p class="text-xs text-gray-400">Fotografías y vídeos en Noticias, Multimedia, Historia y Portada</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($photoResults as $idx => $photo)
                            <div @click="activePhotoIndex = {{ $idx }}; photoLightboxOpen = true"
                                 class="group relative aspect-[4/3] rounded-2xl overflow-hidden bg-gray-900 border border-gray-800 hover:border-amber-500/50 cursor-pointer shadow-lg">
                                @if($photo['type'] === 'video')
                                    <video src="{{ $photo['url'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" muted playsinline></video>
                                @else
                                    <img src="{{ $photo['url'] }}" alt="{{ $photo['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-95 transition-opacity"></div>

                                <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-black/70 text-amber-400 border border-amber-500/30">
                                    {{ $photo['source_badge'] }}
                                </span>

                                <div class="absolute bottom-2.5 left-3 right-3">
                                    <p class="text-xs font-bold text-white line-clamp-1">{{ $photo['title'] }}</p>
                                    <div class="flex items-center justify-between text-[10px] text-gray-300 mt-0.5">
                                        @if(!empty($photo['author']))
                                            <span class="truncate">📸 {{ $photo['author'] }}</span>
                                        @else
                                            <span class="truncate">{{ $photo['subtitle'] ?? '' }}</span>
                                        @endif
                                        @if(!empty($photo['date']))
                                            <span>{{ \Carbon\Carbon::parse($photo['date'])->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Lightbox para Fotos -->
                    <template x-teleport="body">
                        <div x-show="photoLightboxOpen" class="fixed inset-0 z-[110] flex items-center justify-center bg-black/95 backdrop-blur-md p-4" style="display: none;"
                             @keydown.escape.window="photoLightboxOpen = false"
                             @keydown.right.window="if(photoLightboxOpen) activePhotoIndex = (activePhotoIndex + 1) % photoList.length"
                             @keydown.left.window="if(photoLightboxOpen) activePhotoIndex = (activePhotoIndex - 1 + photoList.length) % photoList.length">
                            <button @click="photoLightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white bg-gray-900/80 hover:bg-amber-600 rounded-full p-2.5 z-20">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <button x-show="photoList.length > 1" @click="activePhotoIndex = (activePhotoIndex - 1 + photoList.length) % photoList.length" class="absolute left-4 top-1/2 -translate-y-1/2 text-white bg-black/60 hover:bg-amber-600 rounded-full p-3 z-20">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button x-show="photoList.length > 1" @click="activePhotoIndex = (activePhotoIndex + 1) % photoList.length" class="absolute right-4 top-1/2 -translate-y-1/2 text-white bg-black/60 hover:bg-amber-600 rounded-full p-3 z-20">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>

                            <template x-if="photoList[activePhotoIndex]">
                                <div class="max-w-5xl w-full flex flex-col items-center">
                                    <template x-if="photoList[activePhotoIndex].type === 'video'">
                                        <video :src="photoList[activePhotoIndex].url" class="max-h-[78vh] max-w-full object-contain rounded-xl shadow-2xl" controls autoplay></video>
                                    </template>
                                    <template x-if="photoList[activePhotoIndex].type !== 'video'">
                                        <img :src="photoList[activePhotoIndex].url" class="max-h-[78vh] max-w-full object-contain rounded-xl shadow-2xl">
                                    </template>
                                    <div class="mt-4 text-center bg-gray-900/90 border border-gray-800 px-6 py-3 rounded-2xl max-w-2xl">
                                        <p class="text-white font-bold text-sm sm:text-base" x-text="photoList[activePhotoIndex].title"></p>
                                        <p x-show="photoList[activePhotoIndex].author" class="text-amber-400 text-xs mt-1">
                                            Autor/a: <span x-text="photoList[activePhotoIndex].author"></span>
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </section>
            @endif

            <!-- ========================================================= -->
            <!-- 5. SECCIÓN HISTORIA DE LA BANDA -->
            <!-- ========================================================= -->
            @if(in_array($category, ['all', 'history']) && $historyResults['matched'])
                <section id="resultados-historia" class="mb-14 scroll-mt-28">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Historia de la Banda</h2>
                                <p class="text-xs text-gray-400">Desde 1854 acompañando a Moratalla</p>
                            </div>
                        </div>
                        <a href="{{ url('/#historia') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 flex items-center gap-1">
                            <span>Ver en Portada</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    <div class="glass-panel rounded-2xl p-6 border border-gray-800">
                        @if(!empty($historyResults['excerpts']))
                            <div class="space-y-3 mb-5">
                                @foreach($historyResults['excerpts'] as $excerpt)
                                    <blockquote class="p-4 rounded-xl bg-gray-900/90 border-l-4 border-amber-500 text-gray-300 text-sm leading-relaxed">
                                        "{{ $excerpt }}"
                                    </blockquote>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" @click="openHistoryModal = true" class="px-4 py-2 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-xs font-bold transition-all">
                                Leer Historia Completa Aquí
                            </button>
                            <a href="{{ route('estatutos') }}" class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-gray-300 border border-gray-700 text-xs font-semibold transition-all">
                                Ver Estatutos de la Asociación
                            </a>
                        </div>

                        <!-- Modal Historia Completa -->
                        <template x-teleport="body">
                            <div x-show="openHistoryModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
                                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openHistoryModal = false"></div>
                                <div class="relative bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[88vh] flex flex-col overflow-hidden" @keydown.escape.window="openHistoryModal = false">
                                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                                        <h3 class="text-xl font-bold text-white">Historia de {{ $globalBandName }}</h3>
                                        <button @click="openHistoryModal = false" class="text-gray-400 hover:text-white bg-gray-800 p-2 rounded-full">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-6 overflow-y-auto prose prose-invert prose-amber max-w-none">
                                        {!! $historyResults['full_html'] !!}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            @endif

        @endif
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-900 bg-gray-950 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} {{ $globalBandName }}. Todos los derechos reservados.</p>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="hover:text-amber-400 transition-colors">Inicio</a>
                <a href="{{ route('hemeroteca.news') }}" class="hover:text-amber-400 transition-colors">Hemeroteca Noticias</a>
                <a href="{{ route('hemeroteca.media') }}" class="hover:text-amber-400 transition-colors">Hemeroteca Multimedia</a>
            </div>
        </div>
    </footer>
</body>
</html>
