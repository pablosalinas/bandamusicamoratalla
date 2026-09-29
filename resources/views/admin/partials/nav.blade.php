<ul role="list" class="-mx-2 space-y-1 flex flex-col flex-1" x-data="{
    openInstruments: {{ request()->routeIs('admin.instruments.*') || request()->routeIs('admin.instrument-sections.*') || request()->routeIs('admin.instrument-brands.*') || request()->routeIs('admin.inventory.*') ? 'true' : 'false' }},
    openBoardAccounting: {{ request()->routeIs('admin.boards.*') || request()->routeIs('admin.fiscal-years.*') || request()->routeIs('admin.budget-movements.*') ? 'true' : 'false' }},
    openNewsEvents: {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.media-archive.*') || request()->routeIs('admin.events.*') ? 'true' : 'false' }},
    openUsers: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }},
    openAnalytics: {{ request()->routeIs('admin.analytics.*') || request()->routeIs('admin.logs.*') ? 'true' : 'false' }}
}">
    <!-- Enlace común para todos -->
    <li>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors">
            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
            Panel de Músico
        </a>
    </li>
    <li>
        <a href="{{ route('musician.planning') }}" class="{{ request()->routeIs('musician.planning*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors">
            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('musician.planning*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            Planning
        </a>
    </li>
    <li>
        <a href="{{ route('musician.manual') }}" class="{{ request()->routeIs('musician.manual') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors">
            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('musician.manual') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            Manual de Uso
        </a>
    </li>

    @if(in_array(Auth::user()->role, ['admin', 'treasurer', 'director']) || Auth::user()->isSuperAdmin())
        <div class="pt-4 pb-1">
            <div class="text-xs font-semibold leading-6 text-gray-500 uppercase tracking-wider px-2">Administración</div>
        </div>
        
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors">
                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Panel de Control
            </a>
        </li>

        <!-- 1. GRUPO: MÚSICOS Y USUARIOS -->
        <li>
            <div>
                <button type="button" @click="openUsers = !openUsers" class="w-full flex items-center justify-between gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-colors">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Músicos y Usuarios</span>
                    </div>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openUsers }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="openUsers" x-cloak class="mt-1 pl-11 space-y-1">
                    <a href="{{ route('admin.users.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.users.index') && request('status') != 'pending' ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Listado General
                    </a>
                    <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.users.index') && request('status') == 'pending' ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Pendientes Validación
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.users.create') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        + Añadir Músico / Usuario
                    </a>
                </div>
            </div>
        </li>

        <!-- 2. PARTITURAS (DIRECTO) -->
        <li>
            <a href="{{ route('admin.sheet-music.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.sheet-music.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.sheet-music.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Catálogo Partituras
            </a>
        </li>

        <!-- 3. GRUPO: INSTRUMENTOS, TIPOS, CUERDAS E INVENTARIO -->
        <li>
            <div>
                <button type="button" @click="openInstruments = !openInstruments" class="w-full flex items-center justify-between gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.instruments.*') || request()->routeIs('admin.instrument-sections.*') || request()->routeIs('admin.instrument-brands.*') || request()->routeIs('admin.inventory.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-colors">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.instruments.*') || request()->routeIs('admin.instrument-sections.*') || request()->routeIs('admin.instrument-brands.*') || request()->routeIs('admin.inventory.*') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <span>Instrumentos</span>
                    </div>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openInstruments }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="openInstruments" x-cloak class="mt-1 pl-11 space-y-1">
                    <a href="{{ route('admin.instruments.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.instruments.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Catálogo de Instrumentos
                    </a>
                    <a href="{{ route('admin.instrument-sections.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.instrument-sections.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Cuerdas y Subcuerdas
                    </a>
                    <a href="{{ route('admin.instrument-brands.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.instrument-brands.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Marcas de Instrumentos
                    </a>
                    <a href="{{ route('admin.inventory.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.inventory.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Inventario de la Banda
                    </a>
                </div>
            </div>
        </li>

        <!-- 4. GRUPO: JUNTA DIRECTIVA Y CONTABILIDAD -->
        <li>
            <div>
                <button type="button" @click="openBoardAccounting = !openBoardAccounting" class="w-full flex items-center justify-between gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.boards.*') || request()->routeIs('admin.fiscal-years.*') || request()->routeIs('admin.budget-movements.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-colors">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.boards.*') || request()->routeIs('admin.fiscal-years.*') || request()->routeIs('admin.budget-movements.*') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                        </svg>
                        <span>Directiva y Contabilidad</span>
                    </div>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openBoardAccounting }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="openBoardAccounting" x-cloak class="mt-1 pl-11 space-y-1">
                    <a href="{{ route('admin.boards.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.boards.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Junta Directiva y Actas
                    </a>
                    @if((Auth::user()->isCurrentBoardMember() && in_array(Auth::user()->role, ['admin', 'treasurer', 'director'])) || Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.fiscal-years.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.fiscal-years.*') || request()->routeIs('admin.budget-movements.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Contabilidad y Presupuesto
                    </a>
                    @endif
                </div>
            </div>
        </li>

        <!-- 5. GRUPO: NOTICIAS, EVENTOS Y ARCHIVO SONORO -->
        <li>
            <div>
                <button type="button" @click="openNewsEvents = !openNewsEvents" class="w-full flex items-center justify-between gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.media-archive.*') || request()->routeIs('admin.events.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-colors">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.media-archive.*') || request()->routeIs('admin.events.*') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                        <span>Noticias y Eventos</span>
                    </div>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openNewsEvents }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="openNewsEvents" x-cloak class="mt-1 pl-11 space-y-1">
                    <a href="{{ route('admin.news.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.news.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Noticias y Actividades
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.events.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Eventos y Pasar Lista
                    </a>
                    <a href="{{ route('admin.media-archive.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.media-archive.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Archivo Sonoro / Grabaciones
                    </a>
                </div>
            </div>
        </li>

        <!-- 6. CONFIGURACIÓN Y SISTEMA -->
        <li>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Configuración
            </a>
        </li>
        <!-- 7. GRUPO: ESTADÍSTICAS Y REGISTROS -->
        <li>
            <div>
                <button type="button" @click="openAnalytics = !openAnalytics" class="w-full flex items-center justify-between gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.analytics.*') || request()->routeIs('admin.logs.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-colors">
                    <div class="flex items-center gap-x-3">
                        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('admin.analytics.*') || request()->routeIs('admin.logs.*') ? 'text-amber-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        <span>Estadísticas</span>
                    </div>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openAnalytics }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="openAnalytics" x-cloak class="mt-1 pl-11 space-y-1">
                    <a href="{{ route('admin.analytics.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.analytics.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Estadísticas Web
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="block rounded-md py-1.5 px-2 text-xs font-medium {{ request()->routeIs('admin.logs.*') ? 'text-amber-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        Registros del Sistema
                    </a>
                </div>
            </div>
        </li>
        <li>
            <a href="{{ route('admin.manual') }}" class="{{ request()->routeIs('admin.manual') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                Manual Administrador
            </a>
        </li>
    @endif
    <li class="mt-auto pb-4">
        <a href="{{ url('/') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-white hover:bg-gray-800">
            <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Ir a la Web
        </a>
    </li>
    <li>
        <a href="{{ route('logout') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-400 hover:text-red-400 hover:bg-gray-800">
            <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
            </svg>
            Cerrar Sesión
        </a>
    </li>
</ul>
