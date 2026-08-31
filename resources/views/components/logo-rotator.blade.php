@php
    $logos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
    if (empty($logos)) {
        $logos = ['images/logo.jpg'];
    }
    $bandName = \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla');
@endphp

@if(count($logos) > 0)
    <div class="logo-rotator relative flex items-center justify-center {{ $class ?? 'h-16 w-16' }}" data-interval="10000">
        @foreach($logos as $index => $logoPath)
            @php 
                $src = str_starts_with($logoPath, 'images/') ? asset($logoPath) : asset('storage/' . $logoPath); 
            @endphp
            <img src="{{ $src }}" alt="{{ $bandName }}" class="absolute inset-0 max-w-full max-h-full object-contain transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $index }}">
        @endforeach
    </div>

    <script>
        if (!window.logoRotatorInitialized) {
            window.logoRotatorInitialized = true;
            document.addEventListener('DOMContentLoaded', function() {
                const rotators = document.querySelectorAll('.logo-rotator');
                rotators.forEach(rotator => {
                    const images = rotator.querySelectorAll('img');
                    if (images.length > 1) {
                        let currentIndex = 0;
                        const interval = parseInt(rotator.dataset.interval) || 10000;
                        setInterval(() => {
                            images[currentIndex].classList.remove('opacity-100', 'z-10');
                            images[currentIndex].classList.add('opacity-0', 'z-0');
                            
                            currentIndex = (currentIndex + 1) % images.length;
                            
                            images[currentIndex].classList.remove('opacity-0', 'z-0');
                            images[currentIndex].classList.add('opacity-100', 'z-10');
                        }, interval);
                    }
                });
            });
        }
    </script>
@else
    <span class="font-bold text-lg {{ $textClass ?? 'text-white' }}">{{ $bandName }}</span>
@endif
