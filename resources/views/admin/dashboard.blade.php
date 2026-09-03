<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">
            Panel de Control
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        
        <!-- Stats Card 1: Usuarios -->
        <a href="{{ route('admin.users.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-amber-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-amber-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Total Usuarios / Músicos</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['users'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-amber-500/10 transition-colors">
                <span class="text-sm font-medium text-amber-500">Ver todos</span>
                <svg class="h-4 w-4 text-amber-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 2: Partituras -->
        <a href="{{ route('admin.sheet-music.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-blue-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-blue-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5V15m0 0l-3 3m3-3l3 3M15 19.5V15m0 0l-3 3m3-3l3 3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Partituras</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['sheet_music'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-blue-500/10 transition-colors">
                <span class="text-sm font-medium text-blue-500">Gestionar partituras</span>
                <svg class="h-4 w-4 text-blue-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 3: Instrumentos -->
        <a href="{{ route('admin.instruments.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-indigo-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-indigo-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5v-15m0 0l-3 3m3-3l3 3m6 10.5v-15m0 0l-3 3m3-3l3 3" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Instrumentos</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['instruments'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-indigo-500/10 transition-colors">
                <span class="text-sm font-medium text-indigo-500">Catálogo instrumentos</span>
                <svg class="h-4 w-4 text-indigo-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 4: Inventario -->
        <a href="{{ route('admin.inventory.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-cyan-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-cyan-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Artículos Inventario</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['inventory'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-cyan-500/10 transition-colors">
                <span class="text-sm font-medium text-cyan-500">Gestionar inventario</span>
                <svg class="h-4 w-4 text-cyan-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 5: Junta Directiva -->
        <a href="{{ route('admin.boards.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-emerald-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-emerald-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Junta Directiva</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['board_members'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-emerald-500/10 transition-colors">
                <span class="text-sm font-medium text-emerald-500">Ver junta</span>
                <svg class="h-4 w-4 text-emerald-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 6: Eventos -->
        <a href="{{ route('admin.events.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-rose-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-rose-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Eventos</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['events'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-rose-500/10 transition-colors">
                <span class="text-sm font-medium text-rose-500">Gestor de eventos</span>
                <svg class="h-4 w-4 text-rose-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 7: Noticias -->
        <a href="{{ route('admin.news.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-purple-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-purple-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Noticias Publicadas</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['news'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-purple-500/10 transition-colors">
                <span class="text-sm font-medium text-purple-500">Ver noticias</span>
                <svg class="h-4 w-4 text-purple-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 8: Archivo Multimedia -->
        <a href="{{ route('admin.media-archive.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-fuchsia-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-fuchsia-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Archivos Multimedia</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['media'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-fuchsia-500/10 transition-colors">
                <span class="text-sm font-medium text-fuchsia-500">Galería multimedia</span>
                <svg class="h-4 w-4 text-fuchsia-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 9: Marcas de Instrumentos -->
        <a href="{{ route('admin.instrument-brands.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-teal-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-teal-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655L9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L14.25 2.25 12 8.25h6.75l-2.51 2.69m-5.328 1.48l3.187 3.414" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Marcas Registradas</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['instrument_brands'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-teal-500/10 transition-colors">
                <span class="text-sm font-medium text-teal-500">Gestionar marcas</span>
                <svg class="h-4 w-4 text-teal-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 10: Contabilidad -->
        <a href="{{ route('admin.fiscal-years.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-green-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-green-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Ejercicios Contables</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['fiscal_years'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-green-500/10 transition-colors">
                <span class="text-sm font-medium text-green-500">Ir a contabilidad</span>
                <svg class="h-4 w-4 text-green-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 11: Configuración -->
        <a href="{{ route('admin.settings.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-gray-400/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-gray-400/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Ajustes Web</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">-</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-gray-400/10 transition-colors">
                <span class="text-sm font-medium text-gray-400">Ver configuración</span>
                <svg class="h-4 w-4 text-gray-400 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 12: Estadísticas -->
        <a href="{{ route('admin.analytics.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-orange-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-orange-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Estadísticas de Visitas</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['visits'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-orange-500/10 transition-colors">
                <span class="text-sm font-medium text-orange-500">Ver analíticas</span>
                <svg class="h-4 w-4 text-orange-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 13: Registros -->
        <a href="{{ route('admin.logs.index') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-pink-500/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-pink-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-pink-500/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Registros Actividad</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">{{ $stats['logs'] ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-pink-500/10 transition-colors">
                <span class="text-sm font-medium text-pink-500">Ver logs</span>
                <svg class="h-4 w-4 text-pink-500 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

        <!-- Stats Card 14: Manual -->
        <a href="{{ route('admin.manual') }}" class="block overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-lg relative group hover:border-yellow-400/50 transition-colors cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-yellow-400/20 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="truncate text-sm font-medium text-gray-400">Manual y Ayuda</dt>
                            <dd>
                                <div class="text-3xl font-bold text-white">-</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800/50 px-6 py-3 border-t border-gray-800 flex justify-between items-center group-hover:bg-yellow-400/10 transition-colors">
                <span class="text-sm font-medium text-yellow-400">Ver manual</span>
                <svg class="h-4 w-4 text-yellow-400 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </a>

    </div>

    <!-- Welcome Section -->
    <div class="mt-8 overflow-hidden rounded-xl bg-gray-900 border border-gray-800 shadow-xl relative">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-transparent to-transparent"></div>
        <div class="p-8 relative">
            <h3 class="text-xl font-semibold text-white">¡Bienvenido al Panel de Administración!</h3>
            <p class="mt-2 text-gray-400 max-w-2xl">
                Desde aquí puedes gestionar a todos los miembros de la banda, las partituras asociadas a cada instrumento, publicar noticias y mantener actualizada la composición de la junta directiva. Todo con un control absoluto.
            </p>
        </div>
    </div>
</x-admin-layout>
