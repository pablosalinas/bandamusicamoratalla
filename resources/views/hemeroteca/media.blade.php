<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hemeroteca Multimedia - {{ $globalBandName }}</title>
    
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
                    <a href="{{ route('hemeroteca.news') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-900 border border-gray-800 text-gray-300 hover:text-amber-400 hover:border-amber-500/40 transition-colors">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>Hemeroteca de Noticias</span>
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
                <span class="text-amber-500 font-medium">Hemeroteca Multimedia</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Hemeroteca Multimedia</h1>
                    <p class="text-gray-400 mt-1 text-sm sm:text-base">Archivo sonoro y visual: audios, interpretaciones, vídeos y galerías fotográficas de nuestras actuaciones.</p>
                </div>
                <!-- Pestañas de Hemeroteca -->
                <div class="inline-flex rounded-xl p-1 bg-gray-900 border border-gray-800 self-start md:self-auto">
                    <a href="{{ route('hemeroteca.news') }}" class="px-4 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition-colors">Noticias y Eventos</a>
                    <span class="px-4 py-2 rounded-lg text-xs font-bold bg-amber-600 text-white shadow">Multimedia</span>
                </div>
            </div>
        </div>

        <!-- Filtros y Ordenación -->
        <div class="glass-panel p-5 rounded-2xl mb-10 shadow-xl">
            <form method="GET" action="{{ route('hemeroteca.media') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
                <!-- Ordenación Alfabética (Título / Compositor) -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="sort" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Ordenación</label>
                    <select name="sort" id="sort" class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 px-3 text-sm text-amber-400 font-semibold focus:border-amber-500 focus:ring-1 focus:ring-amber-500" onchange="this.form.submit()">
                        <option value="title" {{ request('sort', 'title') == 'title' ? 'selected' : '' }}>🔤 Título (A - Z)</option>
                        <option value="composer" {{ request('sort') == 'composer' ? 'selected' : '' }}>👤 Compositor (A - Z)</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>📅 Fecha (Más recientes primero)</option>
                    </select>
                </div>

                <!-- Buscador por Título -->
                <div class="lg:col-span-3">
                    <label for="title" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Buscar por Título</label>
                    <div class="relative">
                        <input type="text" name="title" id="title" value="{{ request('title') }}" placeholder="Título de la obra..." class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 pl-10 pr-4 text-sm text-white placeholder-gray-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <svg class="w-4 h-4 text-gray-500 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Buscador por Compositor -->
                <div class="lg:col-span-2">
                    <label for="composer" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Compositor</label>
                    <input list="composers_list" type="text" name="composer" id="composer" value="{{ request('composer') }}" placeholder="Ej: Ferrer Ferran..." class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 px-3 text-sm text-white placeholder-gray-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    <datalist id="composers_list">
                        @foreach($composers as $c)
                            <option value="{{ $c }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Por Año -->
                <div class="lg:col-span-2">
                    <label for="year" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">Año</label>
                    <select name="year" id="year" class="w-full rounded-xl bg-gray-950 border border-gray-800 py-2.5 px-3 text-sm text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <option value="">Todos los años</option>
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones Acción -->
                <div class="sm:col-span-2 lg:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-amber-600 hover:bg-amber-500 py-2.5 px-4 text-sm font-semibold text-white transition-all shadow-md hover:shadow-amber-500/20 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filtrar</span>
                    </button>
                    @if(request()->anyFilled(['title', 'composer', 'year', 'sort', 'from_date', 'to_date', 'type']))
                        <a href="{{ route('hemeroteca.media') }}" class="rounded-xl bg-gray-800 hover:bg-gray-700 py-2.5 px-3 text-sm font-semibold text-gray-300 transition-colors flex items-center justify-center" title="Limpiar filtros">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Listado Multimedia (Grid responsivo de a 3 en pantallas grandes) -->
        @if($mediaArchives->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($mediaArchives as $media)
                    <div x-data="{ openLightbox: false, activeLightboxSlide: 0, lightboxSlides: {{ json_encode($media->images->map(function($i) {
                        $ext = strtolower(pathinfo($i->file_path, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'mov', 'webm', 'avi']);
                        return ['url' => asset('storage/' . $i->file_path), 'type' => $isVideo ? 'video' : 'image'];
                    })) }} }" class="glass-panel rounded-2xl overflow-hidden hover:-translate-y-1.5 transition-all duration-300 group flex flex-col hover:shadow-[0_0_30px_rgba(245,158,11,0.15)]">
                        
                        @if($media->images->count() > 0)
                            <!-- Carrusel de Imágenes Asociadas -->
                            <div class="relative aspect-video bg-gray-900 overflow-hidden" x-data="{ activeSlide: 1, totalSlides: {{ $media->images->count() }} }">
                                @foreach($media->images as $index => $image)
                                    <div x-show="activeSlide === {{ $index + 1 }}" @click="openLightbox = true; activeLightboxSlide = {{ $index }}" class="absolute inset-0 transition-opacity duration-500 ease-in-out cursor-pointer group/img">
                                        @php
                                            $mExt = strtolower(pathinfo($image->file_path, PATHINFO_EXTENSION));
                                            $mIsVideo = in_array($mExt, ['mp4', 'mov', 'webm', 'avi']);
                                        @endphp
                                        @if($mIsVideo)
                                            <video src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-cover select-none transition-transform duration-500 group-hover/img:scale-105" muted loop autoplay playsinline></video>
                                        @else
                                            <img src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-cover select-none transition-transform duration-500 group-hover/img:scale-105">
                                        @endif
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/70 text-white text-xs font-semibold backdrop-blur-md">
                                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                Ver foto en grande
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($media->images->count() > 1)
                                    <button @click.stop="activeSlide = activeSlide > 1 ? activeSlide - 1 : totalSlides" class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-black/60 text-white rounded-full hover:bg-black/90 transition-colors z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                    </button>
                                    <button @click.stop="activeSlide = activeSlide < totalSlides ? activeSlide + 1 : 1" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-black/60 text-white rounded-full hover:bg-black/90 transition-colors z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 z-10">
                                        @foreach($media->images as $index => $image)
                                            <button @click.stop="activeSlide = {{ $index + 1 }}" :class="{'bg-amber-500': activeSlide === {{ $index + 1 }}, 'bg-white/50': activeSlide !== {{ $index + 1 }}}" class="w-2 h-2 rounded-full transition-colors"></button>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="absolute top-2 left-2 z-10 bg-black/70 backdrop-blur-md px-2 py-0.5 rounded text-[11px] font-medium text-amber-400 border border-amber-500/20">
                                    Galería ({{ $media->images->count() }})
                                </div>
                            </div>
                        @endif

                        <!-- Reproductor de Audio o Video -->
                        <div class="relative aspect-video bg-black/70 flex items-center justify-center shrink-0">
                            @if($media->type === 'video')
                                <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover" controls preload="metadata" controlsList="nodownload" oncontextmenu="return false;"></video>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-950/40 via-gray-900 to-black p-4">
                                    <div class="w-12 h-12 rounded-full bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                                    </div>
                                    <audio src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-10 mt-1" controls preload="metadata" controlsList="nodownload" oncontextmenu="return false;"></audio>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-md px-2 py-0.5 rounded text-[11px] font-semibold uppercase tracking-wider {{ $media->type === 'video' ? 'text-cyan-400 border border-cyan-400/20' : 'text-amber-400 border border-amber-400/20' }}">
                                {{ $media->type === 'video' ? 'Vídeo' : 'Grabación Audio' }}
                            </div>
                        </div>

                        <!-- Metadatos de la Obra -->
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-amber-400 transition-colors">{{ $media->title }}</h3>
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if($media->music_type)
                                    <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-0.5 text-xs font-medium text-blue-400 border border-blue-400/20">{{ $media->music_type }}</span>
                                @endif
                                @if($media->composer)
                                    <span class="inline-flex items-center rounded-md bg-gray-800 px-2.5 py-0.5 text-xs font-medium text-amber-300 border border-amber-500/20">
                                        <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ $media->composer }}
                                    </span>
                                @endif
                                @if($media->performance_date)
                                    <span class="inline-flex items-center rounded-md bg-gray-800/60 px-2 py-0.5 text-xs font-medium text-gray-300">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $media->performance_date->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>

                            @if($media->description)
                                <p class="text-xs text-gray-400 leading-relaxed line-clamp-3 mt-auto pt-2 border-t border-gray-800/80">{{ $media->description }}</p>
                            @endif
                        </div>

                        <!-- Lightbox de Fotos -->
                        @if($media->images->count() > 0)
                        <template x-teleport="body">
                            <div x-show="openLightbox" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm" style="display: none;" @keydown.escape.window="openLightbox = false" x-init="$watch('openLightbox', val => { if(!val) $el.querySelectorAll('video').forEach(v => v.pause()) })" @keydown.right.window="if(openLightbox) activeLightboxSlide = (activeLightboxSlide + 1) % lightboxSlides.length" @keydown.left.window="if(openLightbox) activeLightboxSlide = (activeLightboxSlide - 1 + lightboxSlides.length) % lightboxSlides.length">
                                <button @click="openLightbox = false" class="absolute top-6 right-6 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-2 transition-colors z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                
                                <button x-show="lightboxSlides.length > 1" @click="activeLightboxSlide = (activeLightboxSlide - 1 + lightboxSlides.length) % lightboxSlides.length" class="absolute left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-3 transition-colors z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                
                                <button x-show="lightboxSlides.length > 1" @click="activeLightboxSlide = (activeLightboxSlide + 1) % lightboxSlides.length" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-black/50 hover:bg-amber-600 rounded-full p-3 transition-colors z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>

                                <div class="w-full h-full flex flex-col items-center justify-center p-4" x-init="$watch('activeLightboxSlide', () => $el.querySelectorAll('video').forEach(v => v.pause()))">
                                    <template x-for="(slide, index) in lightboxSlides" :key="index">
                                        <div x-show="activeLightboxSlide === index" x-transition.opacity.duration.300ms class="absolute inset-0 flex items-center justify-center p-4 md:p-12 z-[105]">
                                            <template x-if="slide.type === 'video'">
                                                <video :src="slide.url" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl" controls></video>
                                            </template>
                                            <template x-if="slide.type !== 'video'">
                                                <img :src="slide.url" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl">
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <div x-show="lightboxSlides.length > 1" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 bg-black/40 px-4 py-2 rounded-full backdrop-blur-sm z-[110]">
                                    <template x-for="(_, index) in lightboxSlides" :key="index">
                                        <button @click="activeLightboxSlide = index" class="w-3 h-3 rounded-full transition-colors" :class="activeLightboxSlide === index ? 'bg-amber-500' : 'bg-white/40 hover:bg-white/60'"></button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-12">
                {{ $mediaArchives->links() }}
            </div>
        @else
            <div class="text-center text-gray-400 py-16 glass-panel rounded-2xl">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                <h3 class="text-xl font-bold text-white mb-2">No se han encontrado archivos multimedia</h3>
                <p class="text-sm max-w-md mx-auto">No hay audios ni vídeos que coincidan con la búsqueda o los filtros aplicados.</p>
                @if(request()->anyFilled(['title', 'composer', 'year', 'sort']))
                    <a href="{{ route('hemeroteca.media') }}" class="mt-5 inline-flex items-center px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold transition-colors">
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
