<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Estadísticas Web</h2>
    </x-slot>

    <!-- Top Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg p-6">
            <dt class="truncate text-sm font-medium text-gray-400">Total Histórico</dt>
            <dd class="mt-2 text-3xl font-bold tracking-tight text-white">{{ number_format($totalVisits, 0, ',', '.') }}</dd>
        </div>
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg p-6">
            <dt class="truncate text-sm font-medium text-gray-400">Últimos 7 días</dt>
            <dd class="mt-2 text-3xl font-bold tracking-tight text-amber-500">{{ number_format($visitsThisWeek, 0, ',', '.') }}</dd>
        </div>
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg p-6">
            <dt class="truncate text-sm font-medium text-gray-400">Hoy</dt>
            <dd class="mt-2 text-3xl font-bold tracking-tight text-green-500">{{ number_format($visitsToday, 0, ',', '.') }}</dd>
        </div>
    </div>

    <!-- Chart -->
    <div class="mt-8 bg-gray-900 shadow ring-1 ring-white/10 sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold leading-6 text-white mb-4">Visitas (Últimos 30 días)</h3>
        <div class="h-72 w-full">
            <canvas id="visitsChart"></canvas>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Top Paths -->
        <div class="bg-gray-900 shadow ring-1 ring-white/10 sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold leading-6 text-white mb-4">Secciones más visitadas</h3>
            <ul role="list" class="divide-y divide-gray-800">
                @php
                    $friendlyNames = [
                        '/' => '🏠 Inicio (Portada)',
                        'login' => '🔐 Acceso Usuarios',
                        'estatutos' => '📜 Estatutos',
                        'dashboard' => '🎵 Panel de Músico',
                        'planning' => '📅 Planning Músicos',
                        'profile' => '👤 Perfil de Músico',
                        'admin' => '⚙️ Admin: Dashboard',
                        'admin/users' => '👥 Admin: Músicos',
                        'admin/inventory' => '🎺 Admin: Inventario',
                        'admin/instruments' => '📖 Admin: Catálogo',
                        'admin/sheet-music' => '🎼 Admin: Archivo Musical',
                        'admin/events' => '📆 Admin: Eventos',
                        'admin/boards' => '🏛️ Admin: Juntas',
                        'admin/news' => '📰 Admin: Noticias',
                        'admin/analytics' => '📊 Admin: Estadísticas',
                        'admin/logs' => '📝 Admin: Registros',
                        'manual' => '📚 Manual de Ayuda',
                        'admin/manual' => '📚 Manual Admin',
                    ];
                @endphp
                @foreach($topPaths as $path)
                @php
                    $rawPath = $path->path == '' ? '/' : $path->path;
                    $friendlyName = $friendlyNames[$rawPath] ?? ('/' . $rawPath);
                    // Fallback to make it slightly prettier if not in array
                    if (!isset($friendlyNames[$rawPath]) && $rawPath !== '/') {
                        $friendlyName = '🔹 /' . $rawPath;
                    }
                @endphp
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <span class="text-sm font-medium text-gray-200 block">{{ $friendlyName }}</span>
                        @if(isset($friendlyNames[$rawPath]))
                            <span class="text-xs text-gray-500 block">/{{ $rawPath == '/' ? '' : $rawPath }}</span>
                        @endif
                    </div>
                    <span class="text-sm text-amber-500 font-semibold">{{ $path->count }} <span class="text-gray-500 font-normal">visitas</span></span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Devices & Browsers -->
        <div class="space-y-8">
            <div class="bg-gray-900 shadow ring-1 ring-white/10 sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold leading-6 text-white mb-4">Dispositivos</h3>
                <div class="flex items-center justify-between">
                    <div class="text-center w-1/2 border-r border-gray-800">
                        <span class="text-2xl font-bold text-white">{{ $mobilePercent }}%</span>
                        <span class="block text-sm text-gray-400">📱 Móvil</span>
                    </div>
                    <div class="text-center w-1/2">
                        <span class="text-2xl font-bold text-white">{{ $desktopPercent }}%</span>
                        <span class="block text-sm text-gray-400">💻 Ordenador</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 shadow ring-1 ring-white/10 sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold leading-6 text-white mb-4">Navegadores Principales</h3>
                <ul role="list" class="divide-y divide-gray-800">
                    @foreach($topBrowsers as $browser)
                    <li class="py-2 flex justify-between">
                        <span class="text-sm text-gray-300">{{ $browser->browser }}</span>
                        <span class="text-sm text-gray-500">{{ $browser->count }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="bg-gray-900 shadow ring-1 ring-white/10 sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold leading-6 text-white mb-4">Top Países</h3>
                <ul role="list" class="divide-y divide-gray-800">
                    @forelse($topCountries as $country)
                    <li class="py-2 flex justify-between">
                        <span class="text-sm text-gray-300">{{ $country->country }}</span>
                        <span class="text-sm text-gray-500">{{ $country->count }}</span>
                    </li>
                    @empty
                    <li class="py-2 text-sm text-gray-500 text-center">No hay datos geográficos aún.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Visitas Diarias',
                    data: @json($chartValues),
                    borderColor: '#f59e0b', // amber-500
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#f59e0b',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' },
                        grid: { color: '#374151' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { color: '#374151' }
                    }
                }
            }
        });
    </script>
</x-admin-layout>
