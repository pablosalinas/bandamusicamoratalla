<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hemeroteca de Noticias y Eventos - {{ $globalBandName }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
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
                        <span class="text-xs text-gray-400 font-medium tracking-wide">Hemeroteca Histórica</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Enlace destacado al otro módulo de hemeroteca -->
                    <a href="{{ route('hemeroteca.media') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-amber-500/20 to-amber-600/20 text-amber-400 border border-amber-500/40 hover:border-amber-400 hover:text-amber-300 hover:bg-amber-500/30 transition-all shadow-[0_0_15px_rgba(245,158,11,0.2)] hover:shadow-[0_0_20px_rgba(245,158,11,0.4)]">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <span class="hidden xs:inline">Hemeroteca</span>
                        <span>Multimedia</span>
                    </a>

                    <!-- Redes Sociales en Cabecera -->
                    <div class="flex items-center space-x-3 border-l border-gray-800 pl-3 sm:pl-4">
                        <a href="https://www.facebook.com/share/19TzkksMKa/" target="_blank" class="text-gray-400 hover:text-amber-500 transition-colors" title="Facebook">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="https://www.instagram.com/bandamusicademoratalla?igsi=cnU2c2Rpc2d6N2pp" target="_blank" class="text-gray-400 hover:text-amber-500 transition-colors" title="Instagram">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>

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
    <main class="flex-1 pt-28 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
                <a href="{{ url('/') }}" class="hover:text-amber-400 transition-colors">Inicio</a>
                <span>/</span>
                <span class="text-amber-500 font-medium">Hemeroteca de Noticias</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Hemeroteca de Noticias</h1>
                    <p class="text-gray-400 mt-1 text-sm sm:text-base">Archivo cronológico de publicaciones, conciertos y actividades de la banda ordenadas cronológicamente.</p>
                </div>
                <!-- Pestañas de Hemeroteca Destacadas -->
                <div class="inline-flex rounded-xl p-1.5 bg-gray-900/90 border border-amber-500/30 shadow-[0_0_20px_rgba(245,158,11,0.15)] self-start md:self-auto gap-1">
                    <span class="px-4 py-2 rounded-lg text-xs font-extrabold bg-gradient-to-r from-amber-500 to-amber-600 text-black shadow-md flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>Noticias y Eventos</span>
                    </span>
                    <a href="{{ route('hemeroteca.media') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-gray-400 hover:text-amber-400 hover:bg-gray-800/80 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500 hover:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <span>Multimedia</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="glass-panel p-5 rounded-2xl mb-10 shadow-xl">
            <form method="GET" action="{{ route('hemeroteca.news') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
                <!-- Buscador de texto -->
                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="q" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Búsqueda por Título o Contenido</label>
                    <div class="relative">
                        <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Ej: concierto de santa cecilia, certamen..." class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <svg class="w-4 h-4 text-gray-500 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Selector de Año -->
                <div class="lg:col-span-2">
                    <label for="year" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Por Año</label>
                    <select name="year" id="year" class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 px-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <option value="">Todos los años</option>
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div class="lg:col-span-2">
                    <label for="from_date" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Desde Fecha</label>
                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 px-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500" style="color-scheme: dark;">
                </div>

                <!-- Fecha Hasta -->
                <div class="lg:col-span-2">
                    <label for="to_date" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Hasta Fecha</label>
                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 px-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500" style="color-scheme: dark;">
                </div>

                <!-- Botones Acción -->
                <div class="sm:col-span-2 lg:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-amber-600 hover:bg-amber-500 py-2.5 px-4 text-sm font-semibold text-white transition-all shadow-md hover:shadow-amber-500/20 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filtrar</span>
                    </button>
                    @if(request()->anyFilled(['q', 'year', 'from_date', 'to_date']))
                        <a href="{{ route('hemeroteca.news') }}" class="rounded-xl bg-gray-800 hover:bg-gray-700 py-2.5 px-3 text-sm font-semibold text-gray-300 transition-colors flex items-center justify-center" title="Limpiar filtros">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Grid de Noticias (en filas de a 3, responsive) -->
        @if($news->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($news as $item)
                    <div x-init="$watch('openNews', val => { 
                            if(!val) {
                                $el.querySelectorAll('video').forEach(v => { v.pause(); v.currentTime = 0; });
                                if(newsSlides.length > 1) startAutoplay();
                            } else {
                                stopAutoplay();
                                if(newsSlides.length > 1) startAutoplay();
                            }
                        })" 
                         x-data="{ 
                            openNews: false, 
                            activeNewsSlide: 0, 
                            timer: null,
                            speed: {{ ($newsSpeed ?? 4) * 1000 }},
                            waitVideosFinish: {{ $waitVideosFinish ? 'true' : 'false' }},
                            newsSlides: {{ json_encode($item->newsImages->map(function($i) { 
                                $ext = strtolower(pathinfo($i->url, PATHINFO_EXTENSION));
                                $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                                return ['url' => $i->url, 'desc' => $i->description, 'type' => $isVideo ? 'video' : 'image']; 
                            })) }},
                            init() {
                                if (this.newsSlides.length > 1) {
                                    this.startAutoplay();
                                }
                            },
                            startAutoplay() {
                                this.stopAutoplay();
                                if (this.newsSlides.length <= 1) return;
                                
                                this.$nextTick(() => {
                                    let currentSlide = this.newsSlides[this.activeNewsSlide];
                                    if (this.openNews) {
                                        let modalContainer = document.getElementById('modal-news-hemeroteca-{{ $item->id }}');
                                        if (!currentSlide || currentSlide.type !== 'video' || !this.waitVideosFinish) {
                                            if (currentSlide && currentSlide.type === 'video' && modalContainer) {
                                                let videoEl = modalContainer.querySelector('video');
                                                if (videoEl) {
                                                    videoEl.currentTime = 0;
                                                    let playPromise = videoEl.play();
                                                    if (playPromise !== undefined) playPromise.catch(() => {});
                                                }
                                            }
                                            this.timer = setTimeout(() => { this.next(); }, this.speed);
                                        } else {
                                            let videoEl = modalContainer ? modalContainer.querySelector('video') : null;
                                            if (videoEl) {
                                                videoEl.currentTime = 0;
                                                let playPromise = videoEl.play();
                                                if (playPromise !== undefined) playPromise.catch(() => {});
                                                videoEl.onended = () => { videoEl.onended = null; this.next(); };
                                            } else {
                                                this.timer = setTimeout(() => { this.next(); }, this.speed);
                                            }
                                        }
                                    } else {
                                        let container = this.$el.querySelector('.card-news-carousel');
                                        let videoEl = container ? container.querySelector('video') : null;
                                        if (videoEl) {
                                            videoEl.currentTime = 0;
                                            let playPromise = videoEl.play();
                                            if (playPromise !== undefined) playPromise.catch(() => {});
                                        }
                                        this.timer = setTimeout(() => { this.next(); }, this.speed);
                                    }
                                });
                            },
                            stopAutoplay() {
                                if (this.timer) { clearTimeout(this.timer); this.timer = null; }
                                if (this.$el) { this.$el.querySelectorAll('video').forEach(v => { v.onended = null; }); }
                                let modalContainer = document.getElementById('modal-news-hemeroteca-{{ $item->id }}');
                                if (modalContainer) { modalContainer.querySelectorAll('video').forEach(v => { v.onended = null; }); }
                            },
                            next() {
                                this.stopAutoplay();
                                this.activeNewsSlide = (this.activeNewsSlide + 1) % this.newsSlides.length;
                                this.startAutoplay();
                            },
                            prev() {
                                this.stopAutoplay();
                                this.activeNewsSlide = (this.activeNewsSlide - 1 + this.newsSlides.length) % this.newsSlides.length;
                                this.startAutoplay();
                            }
                         }" 
                         class="glass-panel rounded-2xl overflow-hidden hover:shadow-[0_0_30px_rgba(245,158,11,0.15)] transition-all duration-500 transform hover:-translate-y-2 flex flex-col cursor-pointer group" 
                         @click="openNews = true"
                         @mouseenter="if(!openNews) stopAutoplay()" 
                         @mouseleave="if(newsSlides.length > 1 && !openNews) startAutoplay()">
                        
                        @if($item->newsImages->count() > 0)
                            <div class="h-52 w-full overflow-hidden relative bg-gray-900 card-news-carousel">
                                <template x-for="(slide, index) in newsSlides" :key="index">
                                    <div x-show="activeNewsSlide === index"
                                         x-transition:enter="transition ease-out duration-700"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-500 absolute inset-0"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-105"
                                         class="absolute inset-0 w-full h-full">
                                        <template x-if="slide.type === 'video'">
                                            <video :src="slide.url" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" muted loop autoplay playsinline></video>
                                        </template>
                                        <template x-if="slide.type !== 'video'">
                                            <img :src="slide.url" :alt="slide.desc || '{{ addslashes($item->title) }}'" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        </template>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors pointer-events-none"></div>

                                <div x-show="newsSlides.length > 1" class="absolute top-2.5 left-2.5 bg-black/60 backdrop-blur-md text-amber-400 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-amber-500/30 flex items-center gap-1 shadow-lg pointer-events-none z-10">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span><span x-text="activeNewsSlide + 1"></span>/<span x-text="newsSlides.length"></span></span>
                                </div>
                            </div>
                        @elseif($item->mainImage)
                            @php
                                $mainExt = strtolower(pathinfo($item->mainImage->url, PATHINFO_EXTENSION));
                                $mainIsVideo = in_array($mainExt, ['mp4', 'mov', 'webm', 'avi']);
                            @endphp
                            <div class="h-52 w-full overflow-hidden relative bg-gray-900">
                                @if($mainIsVideo)
                                    <video src="{{ $item->mainImage->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" muted loop autoplay playsinline></video>
                                @else
                                    <img src="{{ $item->mainImage->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @endif
                                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors pointer-events-none"></div>
                            </div>
                        @endif

                        <div class="p-6 flex-1 flex flex-col">
                            @php
                                $referenceDate = $item->event_date ?? $item->active_from;
                                $referenceLabel = $item->event_date ? 'Evento' : ($item->active_from ? 'Vigente' : null);
                            @endphp
                            <div class="space-y-1 mb-3 text-xs">
                                <div class="flex items-center justify-between text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Publicado: {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="text-gray-500 lowercase">{{ $item->created_at->locale('es')->diffForHumans() }}</span>
                                </div>
                                @if($referenceDate)
                                    <div class="flex items-center justify-between font-semibold text-amber-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                            {{ $referenceLabel }}: {{ $referenceDate->format('d/m/Y') }}
                                        </span>
                                        <span class="text-amber-400/90 lowercase text-[11px]">{{ $referenceDate->locale('es')->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-amber-400 transition-colors">{{ $item->title }}</h3>
                            <p class="text-gray-400 leading-relaxed line-clamp-3 mb-6 flex-1 text-sm">
                                {{ Str::limit(html_entity_decode(strip_tags($item->content), ENT_QUOTES, 'UTF-8'), 140) }}
                            </p>
                            
                            <div class="mt-auto pt-4 border-t border-gray-800/80 flex items-center justify-between text-amber-500 text-sm font-semibold">
                                <span class="flex items-center group-hover:translate-x-1 transition-transform">
                                    Leer completa
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                @if($item->newsImages->count() > 1)
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $item->newsImages->count() }} imágenes
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Noticia Detallada -->
                        <template x-teleport="body">
                            <div x-show="openNews" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
                                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openNews = false"></div>
                                <div class="relative bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden" @keydown.escape.window="openNews = false">
                                    <!-- Header Modal -->
                                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                                        <h3 class="text-xl font-bold text-white truncate pr-4">{{ $item->title }}</h3>
                                        <button @click="openNews = false" class="text-gray-400 hover:text-white transition-colors bg-gray-800 p-2 rounded-full hover:bg-gray-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Body Modal -->
                                    <div class="p-6 overflow-y-auto">
                                        @if($item->newsImages->count() > 0)
                                            <!-- Carrusel dentro de modal -->
                                            <div id="modal-news-hemeroteca-{{ $item->id }}" 
                                                 class="relative rounded-xl overflow-hidden bg-black mb-8 aspect-video flex items-center justify-center group/carousel modal-news-carousel" 
                                                 x-init="$watch('activeNewsSlide', () => $el.querySelectorAll('video').forEach(v => v.pause()))">
                                                <template x-for="(slide, index) in newsSlides" :key="index">
                                                    <div x-show="activeNewsSlide === index" 
                                                         x-transition:enter="transition ease-out duration-500"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-105"
                                                         class="absolute inset-0 w-full h-full flex items-center justify-center">
                                                        <template x-if="slide.type === 'video'">
                                                            <video :src="slide.url" class="w-full h-full object-contain" controls autoplay></video>
                                                        </template>
                                                        <template x-if="slide.type !== 'video'">
                                                            <img :src="slide.url" :alt="slide.desc || '{{ addslashes($item->title) }}'" class="w-full h-full object-contain">
                                                        </template>
                                                    </div>
                                                </template>
                                                
                                                @if($item->newsImages->count() > 1)
                                                    <button @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 p-2 bg-black/60 text-white rounded-full hover:bg-black/90 transition-colors z-20">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                    </button>
                                                    <button @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 bg-black/60 text-white rounded-full hover:bg-black/90 transition-colors z-20">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    </button>
                                                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-20">
                                                        <template x-for="(slide, index) in newsSlides" :key="index">
                                                            <button @click.stop="stopAutoplay(); activeNewsSlide = index; startAutoplay()" 
                                                                    :class="{'bg-amber-500 w-6': activeNewsSlide === index, 'bg-white/50 w-2': activeNewsSlide !== index}" 
                                                                    class="h-2 rounded-full transition-all duration-300"></button>
                                                        </template>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <!-- Texto Noticia -->
                                        <div class="prose prose-invert prose-amber max-w-none">
                                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-400 mb-6 border-b border-gray-800 pb-4">
                                                <span>📅 Publicado: {{ $item->created_at->format('d/m/Y') }} <span class="text-xs text-gray-500 lowercase">({{ $item->created_at->locale('es')->diffForHumans() }})</span></span>
                                                @if($referenceDate)
                                                    <span class="text-amber-500 font-medium">🎫 {{ $referenceLabel }}: {{ $referenceDate->format('d/m/Y') }} <span class="text-xs text-amber-400/80 lowercase">({{ $referenceDate->locale('es')->diffForHumans() }})</span></span>
                                                @endif
                                            </div>
                                            {!! $item->content !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-12">
                {{ $news->links() }}
            </div>
        @else
            <div class="text-center text-gray-400 py-16 glass-panel rounded-2xl">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <h3 class="text-xl font-bold text-white mb-2">No se han encontrado publicaciones</h3>
                <p class="text-sm max-w-md mx-auto">No hay resultados que coincidan con los criterios de búsqueda especificados.</p>
                @if(request()->anyFilled(['q', 'year', 'from_date', 'to_date']))
                    <a href="{{ route('hemeroteca.news') }}" class="mt-5 inline-flex items-center px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold transition-colors">
                        Restablecer todos los filtros
                    </a>
                @endif
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 bg-gray-950 pt-16 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="cursor-pointer shrink-0">
                        <x-logo-rotator class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full overflow-hidden" />
                    </a>
                    <div class="flex flex-col">
                        <div class="flex flex-col leading-none">
                            <span class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">{{ $globalBandName }}</span>
                        </div>
                        <span class="text-xs sm:text-sm lg:text-base font-medium italic tracking-widest mt-1 animate-slogan">{{ \App\Models\SiteSetting::getSetting('site_slogan', 'Tu banda') }}</span>
                    </div>
                </div>
                <p class="text-gray-500 mt-2 text-sm">Cultura y tradición musical en el Noroeste Murciano.</p>
                <div class="mt-4 flex items-center gap-2 text-sm text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <a href="mailto:bandamusicademoratalla@gmail.com" class="hover:text-amber-500 transition-colors">bandamusicademoratalla@gmail.com</a>
                </div>
            </div>
            <div class="flex space-x-6 mt-6 md:mt-0">
                <!-- Social links placeholders -->
                <a href="https://www.facebook.com/share/19TzkksMKa/" target="_blank" class="text-gray-400 hover:text-amber-500 transition-colors">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                </a>
                <a href="https://www.instagram.com/bandamusicademoratalla?igsi=cnU2c2Rpc2d6N2pp" target="_blank" class="text-gray-400 hover:text-amber-500 transition-colors">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                </a>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-900 pt-8 flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto px-6 lg:px-8 text-sm text-gray-600">
            <p>
                &copy; {{ date('Y') == '2026' ? '2026' : '2026 - ' . date('Y') }} Asociación Banda de Música de Moratalla. Todos los derechos reservados. 
                <span class="block sm:inline mt-2 sm:mt-0 sm:ml-2">
                    Diseñado por <a href="https://www.moratalla-murcia.com" target="_blank" class="text-amber-500 hover:text-amber-400 transition-colors">@ www.moratalla-murcia.com</a> - {{ date('Y') == '2026' ? '2026' : '2026 - ' . date('Y') }}
                </span>
            </p>
            <div class="mt-4 md:mt-0 space-x-4 flex items-center">
                <span class="text-gray-700 text-xs mr-2" title="Visitas">{{ number_format($visit_count ?? \App\Models\SiteSetting::getSetting('visit_count', 0)) }}</span>
                <a href="{{ route('estatutos') }}" class="hover:text-amber-500">Estatutos</a>
                <a href="{{ route('legal') }}" class="hover:text-amber-500">Aviso Legal</a>
                <a href="{{ route('legal') }}" class="hover:text-amber-500">Privacidad</a>
            </div>
        </div>
    </footer>
</body>
</html>
