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

                <div class="flex items-center gap-4">
                    <a href="{{ route('hemeroteca.media') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-900 border border-gray-800 text-gray-300 hover:text-amber-400 hover:border-amber-500/40 transition-colors">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <span>Hemeroteca Multimedia</span>
                    </a>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-amber-500 hover:text-amber-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Volver a Inicio</span>
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
                <!-- Pestañas de Hemeroteca -->
                <div class="inline-flex rounded-xl p-1 bg-gray-900 border border-gray-800 self-start md:self-auto">
                    <span class="px-4 py-2 rounded-lg text-xs font-bold bg-amber-600 text-white shadow">Noticias y Eventos</span>
                    <a href="{{ route('hemeroteca.media') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition-colors">Multimedia</a>
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
                            <div class="flex items-center justify-between text-xs text-amber-500 font-semibold tracking-wide uppercase mb-3">
                                <span>{{ $item->event_date ? 'Evento: ' . $item->event_date->format('d/m/Y') : $item->created_at->format('d/m/Y') }}</span>
                                <span class="text-gray-500 font-normal lowercase">{{ $item->created_at->diffForHumans() }}</span>
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
                                            <div class="flex items-center gap-4 text-sm text-gray-400 mb-6 border-b border-gray-800 pb-4">
                                                @if(!$item->event_date)
                                                    <span>📅 Publicado: {{ $item->created_at->format('d/m/Y') }}</span>
                                                @endif
                                                @if($item->event_date)
                                                    <span class="text-amber-500 font-medium">🎫 Evento: {{ $item->event_date->format('d/m/Y') }}</span>
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
    <footer class="border-t border-gray-800 bg-gray-950/80 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500">
            <p>© {{ date('Y') }} {{ $globalBandName }} - Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
