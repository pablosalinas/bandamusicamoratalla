<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Administración y Configuración</h2>
    </x-slot>

    <div x-data="{ tab: '{{ request('tab', 'general') }}' }" class="mt-8 flex flex-col md:flex-row gap-6">
        <!-- Sidebar Tabs -->
        <div class="w-full md:w-64 flex-shrink-0">
            <nav class="flex flex-col space-y-1">
                <button @click="tab = 'general'" :class="{ 'bg-gray-800 text-white': tab === 'general', 'text-gray-400 hover:bg-gray-800 hover:text-white': tab !== 'general' }" class="px-4 py-3 rounded-md font-medium text-left transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Ajustes Generales
                </button>
                <button @click="tab = 'tarjetas'" :class="{ 'bg-gray-800 text-white': tab === 'tarjetas', 'text-gray-400 hover:bg-gray-800 hover:text-white': tab !== 'tarjetas' }" class="px-4 py-3 rounded-md font-medium text-left transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Tarjetas del Panel
                </button>
                <button @click="tab = 'textos'" :class="{ 'bg-gray-800 text-white': tab === 'textos', 'text-gray-400 hover:bg-gray-800 hover:text-white': tab !== 'textos' }" class="px-4 py-3 rounded-md font-medium text-left transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Textos y Documentos
                </button>
                <button @click="tab = 'apariencia'" :class="{ 'bg-gray-800 text-white': tab === 'apariencia', 'text-gray-400 hover:bg-gray-800 hover:text-white': tab !== 'apariencia' }" class="px-4 py-3 rounded-md font-medium text-left transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Apariencia y Multimedia
                </button>
                <button @click="tab = 'backup'" :class="{ 'bg-gray-800 text-white': tab === 'backup', 'text-gray-400 hover:bg-gray-800 hover:text-white': tab !== 'backup' }" class="px-4 py-3 rounded-md font-medium text-left transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Copia de Seguridad
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1 max-w-4xl pb-12">
            
            <!-- GENERAL SETTINGS -->
            <div x-show="tab === 'general'" x-cloak>
                <h3 class="text-2xl font-semibold text-white mb-6">Ajustes Generales</h3>
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
                    @csrf
                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="band_name" class="block text-sm font-medium leading-6 text-white">Nombre de la Banda</label>
                                <p class="text-sm text-gray-400 mb-2">Este nombre se mostrará en la página principal y en el panel.</p>
                                <input type="text" name="band_name" id="band_name" value="{{ old('band_name', $settings['band_name']) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            <div class="sm:col-span-6">
                                <label for="site_slogan" class="block text-sm font-medium leading-6 text-white">Eslogan</label>
                                <p class="text-sm text-gray-400 mb-2">Frase corta que aparecerá en la web principal debajo del nombre.</p>
                                <input type="text" name="site_slogan" id="site_slogan" value="{{ old('site_slogan', $settings['site_slogan'] ?? '') }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            <div class="sm:col-span-3">
                                <label for="session_timeout" class="block text-sm font-medium leading-6 text-white">Tiempo de sesión (Minutos)</label>
                                <p class="text-sm text-gray-400 mb-2">Tiempo de inactividad antes de cerrar sesión automáticamente.</p>
                                <input type="number" name="session_timeout" id="session_timeout" value="{{ old('session_timeout', $settings['session_timeout']) }}" required min="1" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            @if(auth()->user()->canViewIban())
                            <div class="sm:col-span-6">
                                <label for="band_iban" class="block text-sm font-medium leading-6 text-amber-500">Cuenta Bancaria de la Banda (IBAN)</label>
                                <p class="text-sm text-gray-400 mb-2">Se guardará de forma encriptada en la base de datos y solo será visible para el tesorero.</p>
                                <input type="text" name="band_iban" id="band_iban" value="{{ old('band_iban', $settings['band_iban']) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-amber-500/50 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" placeholder="ES00 0000 0000 0000 0000 0000">
                            </div>
                            @endif
                            @if(auth()->user()->isSuperAdmin())
                            <div class="sm:col-span-6 mt-4">
                                <label for="backup_password" class="block text-sm font-medium leading-6 text-amber-500">Contrase&ntilde;a del Archivo Backup (ZIP)</label>
                                <p class="text-sm text-gray-400 mb-2">Se guardar&aacute; encriptada y s&oacute;lo el superusuario puede gestionarla. El ZIP generado requerir&aacute; esta clave para abrirse.</p>
                                <input type="text" name="backup_password" id="backup_password" value="{{ old('backup_password', $backupPassword) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-amber-500/50 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" placeholder="@Acemila2026">
                            </div>
                            @endif
<input type="hidden" name="carousel_speed" value="{{ old('carousel_speed', $settings['carousel_speed'] ?? 4) }}">
                        </div>
                    </div>
                    <div class="flex items-center justify-end px-4 py-4 sm:px-8 border-t border-gray-800">
                        <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-amber-600">
                            Guardar Ajustes Generales
                        </button>
                    </div>
                </form>
            </div>

            <!-- TARJETAS DEL PANEL DE CONTROL -->
            <div x-show="tab === 'tarjetas'" x-cloak x-data="{
                selectAll() {
                    this.$el.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = true);
                },
                deselectAll() {
                    this.$el.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = false);
                }
            }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-2xl font-semibold text-white">Tarjetas del Panel de Control</h3>
                        <p class="text-sm text-gray-400 mt-1">Activa o desactiva las tarjetas de acceso rápido y estadísticas que se muestran en el Panel de Control principal.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="selectAll()" class="rounded-md bg-gray-800 hover:bg-gray-700 px-3 py-1.5 text-xs font-medium text-gray-300 transition-colors border border-gray-700">
                            Activar todas
                        </button>
                        <button type="button" @click="deselectAll()" class="rounded-md bg-gray-800 hover:bg-gray-700 px-3 py-1.5 text-xs font-medium text-gray-300 transition-colors border border-gray-700">
                            Desactivar todas
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.settings.dashboard-cards.update') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
                    @csrf
                    <div class="p-6 divide-y divide-gray-800/80">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6">
                            @foreach($dashboardCards as $cardKey => $card)
                                <label for="card_{{ $cardKey }}" class="flex items-start justify-between p-4 rounded-xl bg-gray-950/60 border border-gray-800/80 hover:border-gray-700 transition-colors cursor-pointer group">
                                    <div class="flex items-start gap-3.5 pr-4">
                                        <div class="p-2.5 rounded-lg bg-gray-800/80 group-hover:scale-105 transition-transform text-amber-500 shrink-0 mt-0.5">
                                            @if($card['icon'] === 'users')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                            @elseif($card['icon'] === 'sheet_music')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5V15m0 0l-3 3m3-3l3 3M15 19.5V15m0 0l-3 3m3-3l3 3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            @elseif($card['icon'] === 'instruments')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5v-15m0 0l-3 3m3-3l3 3m6 10.5v-15m0 0l-3 3m3-3l3 3" /></svg>
                                            @elseif($card['icon'] === 'inventory')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                            @elseif($card['icon'] === 'boards')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" /></svg>
                                            @elseif($card['icon'] === 'events')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                            @elseif($card['icon'] === 'news')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" /></svg>
                                            @elseif($card['icon'] === 'media')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                            @elseif($card['icon'] === 'brands')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655L9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L14.25 2.25 12 8.25h6.75l-2.51 2.69m-5.328 1.48l3.187 3.414" /></svg>
                                            @elseif($card['icon'] === 'accounting')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                                            @elseif($card['icon'] === 'settings')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /></svg>
                                            @elseif($card['icon'] === 'analytics')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                                            @elseif($card['icon'] === 'logs')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-white group-hover:text-amber-400 transition-colors">{{ $card['title'] }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ $card['description'] }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Toggle Switch -->
                                    <div class="relative inline-flex items-center shrink-0 pt-1">
                                        <input type="checkbox" name="cards[{{ $cardKey }}]" id="card_{{ $cardKey }}" value="1" {{ $card['enabled'] ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[6px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600 border border-gray-700"></div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-4 py-4 sm:px-8 border-t border-gray-800 bg-gray-900/50 rounded-b-xl">
                        <span class="text-xs text-gray-400">Los cambios se aplicarán inmediatamente en la pantalla principal del panel.</span>
                        <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-amber-600 transition-colors">
                            Guardar Tarjetas
                        </button>
                    </div>
                </form>
            </div>

            <!-- TEXTOS Y DOCUMENTOS -->
            <div x-show="tab === 'textos'" x-cloak>
                <h3 class="text-2xl font-semibold text-white mb-6">Textos y Documentos</h3>
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
                    @csrf
                    <!-- Preserve hidden required fields so validation doesn't fail -->
                    <input type="hidden" name="band_name" value="{{ $settings['band_name'] }}">
                    <input type="hidden" name="session_timeout" value="{{ $settings['session_timeout'] }}">
                    <input type="hidden" name="carousel_speed" value="{{ $settings['carousel_speed'] ?? 4 }}">
                    
                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="statutes" class="block text-sm font-medium leading-6 text-white">Estatutos</label>
                                <p class="text-sm text-gray-400 mb-2">Texto de los estatutos. Los usuarios podrán leerlos y descargarlos en PDF.</p>
                                <div class="mt-2 text-black">
                                    <textarea id="statutes" name="statutes" rows="10">{{ old('statutes', $settings['statutes'] ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <label for="band_history" class="block text-sm font-medium leading-6 text-white">Historia de la Banda</label>
                                <p class="text-sm text-gray-400 mb-2">Descripción de la historia de la banda de música que se mostrará en la página principal.</p>
                                <div class="mt-2 text-black">
                                    <textarea id="band_history" name="band_history" rows="10">{{ old('band_history', $settings['band_history'] ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                                <h3 class="text-lg font-bold leading-tight tracking-tight text-white mb-2">Modelo de Justificante Parental</h3>
                                <p class="text-sm text-gray-400 mb-4">Los músicos menores de edad podrán descargar este modelo desde su panel. Puedes subir un PDF directamente, o redactarlo aquí con texto enriquecido. Si redactas texto, tendrá prioridad.</p>
                                
                                <div class="mb-4">
                                    <label for="parental_consent_pdf" class="block text-sm font-medium leading-6 text-white">Subir Archivo PDF (Opcional si usas el editor abajo)</label>
                                    <input type="file" name="parental_consent_pdf" id="parental_consent_pdf" accept="application/pdf" class="mt-2 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                                    @if(isset($settings['parental_consent_pdf']) && $settings['parental_consent_pdf'])
                                        <p class="mt-2 text-sm text-green-400">📄 Hay un PDF subido actualmente.</p>
                                    @endif
                                </div>

                                <label for="parental_consent_template" class="block text-sm font-medium leading-6 text-white">Editor de Texto Enriquecido (Prioridad sobre PDF)</label>
                                <div class="mt-2 text-black">
                                    <textarea id="parental_consent_template" name="parental_consent_template" rows="10">{{ old('parental_consent_template', $settings['parental_consent_template'] ?? '') }}</textarea>
                                </div>
                                <div class="mt-4 flex gap-4">
                                    <a href="{{ route('admin.settings.parental-consent.download') }}" target="_blank" class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 ring-1 ring-inset ring-gray-600">
                                        Descargar / Imprimir Prueba de PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end px-4 py-4 sm:px-8 border-t border-gray-800">
                        <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-amber-600">
                            Guardar Textos
                        </button>
                    </div>
                </form>
            </div>

            <!-- APARIENCIA -->
            <div x-show="tab === 'apariencia'" x-cloak>
                <h3 class="text-2xl font-semibold text-white mb-6">Apariencia y Multimedia</h3>
                
                <!-- Velocidad del carrusel -->
                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl mb-8">
                    @csrf
                    <input type="hidden" name="band_name" value="{{ $settings['band_name'] }}">
                    <input type="hidden" name="session_timeout" value="{{ $settings['session_timeout'] }}">
                    
                    <div class="px-4 py-4 sm:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <label for="carousel_speed" class="block text-sm font-medium leading-6 text-white">Velocidad del Carrusel (Segundos)</label>
                            <p class="text-sm text-gray-400">Tiempo que tarda en pasar de una foto a otra en la página principal.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" name="carousel_speed" id="carousel_speed" value="{{ old('carousel_speed', $settings['carousel_speed'] ?? 4) }}" required min="1" class="block w-24 rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm text-center">
                            <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">Guardar</button>
                        </div>
                    </div>
                </form>

                <!-- Logos -->
                <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl mb-8">
                    <div class="px-4 py-6 sm:p-8">
                        <h4 class="text-xl font-bold text-white mb-2">Logos de la Banda</h4>
                        <p class="text-sm text-gray-400 mb-6">Logos para la web pública, panel y login. Si subes más de uno, rotarán automáticamente.</p>
                        
                        <form action="{{ route('admin.settings.logos.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 border-b border-gray-800 pb-6">
                            @csrf
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <input type="file" name="logos[]" id="logos" multiple accept="image/*" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                                <button type="submit" class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 flex-shrink-0">Subir Logos</button>
                            </div>
                        </form>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @forelse($settings['site_logos'] ?? [] as $logoObj)
                                @php 
                                    $logoPath = is_array($logoObj) ? $logoObj['path'] : $logoObj;
                                    $logoOrder = is_array($logoObj) ? ($logoObj['order'] ?? 999) : 999;
                                    $src = str_starts_with($logoPath, 'images/') ? asset($logoPath) : asset('storage/' . $logoPath); 
                                @endphp
                                <div class="flex flex-col bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                                    <div class="relative group aspect-square flex items-center justify-center p-2 bg-gray-900">
                                        <img src="{{ $src }}" class="max-w-full max-h-full object-contain">
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <form action="{{ route('admin.settings.logos.destroy') }}" method="POST" onsubmit="return confirm('¿Eliminar logo?');">
                                                @csrf @method('DELETE') <input type="hidden" name="path" value="{{ $logoPath }}">
                                                <button type="submit" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="p-2 border-t border-gray-700 bg-gray-800">
                                        <form action="{{ route('admin.settings.logos.update') }}" method="POST" class="flex items-center gap-2">
                                            @csrf @method('PUT') <input type="hidden" name="path" value="{{ $logoPath }}">
                                            <input type="number" name="order" value="{{ $logoOrder !== 999 ? $logoOrder : '' }}" placeholder="Nº" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs text-center p-1">
                                            <button type="submit" class="rounded-md bg-gray-700 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-gray-600">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-full text-sm text-gray-500 italic">No hay logos subidos.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Carrusel -->
                <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl mb-8">
                    <div class="px-4 py-6 sm:p-8">
                        <h4 class="text-xl font-bold text-white mb-2">Medios del Carrusel (Portada)</h4>
                        
                        <form action="{{ route('admin.settings.carousel.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 border-b border-gray-800 pb-6">
                            @csrf
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <input type="file" name="media[]" id="media" multiple accept="image/*,video/mp4" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                                <button type="submit" class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 flex-shrink-0">Subir</button>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @forelse($carouselMedia as $media)
                                <div class="flex flex-col bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                                    <div class="relative group aspect-video flex items-center justify-center bg-gray-900">
                                        @if($media->type === 'image')
                                            <img src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover" muted></video>
                                        @endif
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <form action="{{ route('admin.settings.carousel.destroy', $media) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="p-2 border-t border-gray-700 bg-gray-800">
                                        <form action="{{ route('admin.settings.carousel.update', $media) }}" method="POST" class="flex gap-2">
                                            @csrf @method('PUT')
                                            <input type="text" name="description" value="{{ $media->description }}" placeholder="Descripción" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 sm:text-xs">
                                            <button type="submit" class="rounded-md bg-gray-700 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-gray-600">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-full text-sm text-gray-500 italic">No hay archivos en el carrusel.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Band History Images -->
                <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
                    <div class="px-4 py-6 sm:p-8">
                        <h4 class="text-xl font-bold text-white mb-2">Imágenes de Historia de la Banda</h4>
                        
                        <form action="{{ route('admin.settings.band-history-images.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 border-b border-gray-800 pb-6">
                            @csrf
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <input type="file" name="image" id="bh_image" accept="image/*" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                                <button type="submit" class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 flex-shrink-0">Añadir Imagen</button>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($bandHistoryImages as $image)
                                <div class="flex flex-col bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                                    <div class="relative group aspect-video">
                                        <img src="{{ $image->url }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <form action="{{ route('admin.settings.band-history-images.destroy', $image) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                            </form>
                                        </div>
                                        @if($loop->first)
                                            <div class="absolute top-2 left-2 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded shadow">IMAGEN PRINCIPAL</div>
                                        @endif
                                    </div>
                                    <div class="p-3 border-t border-gray-700 bg-gray-800">
                                        <form action="{{ route('admin.settings.band-history-images.update', $image) }}" method="POST" class="flex flex-col gap-2">
                                            @csrf @method('PUT')
                                            <div class="flex items-center gap-2">
                                                <label class="text-xs text-gray-400 w-12">Orden:</label>
                                                <input type="number" name="sort_order" value="{{ $image->sort_order }}" class="block w-16 rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm sm:text-xs text-center">
                                            </div>
                                            <input type="text" name="description" value="{{ $image->description }}" placeholder="Descripción..." class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm sm:text-xs">
                                            <button type="submit" class="rounded-md bg-gray-700 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-gray-600 mt-1">Guardar cambios</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-full text-sm text-gray-500 italic">No hay imágenes en Historia.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- BACKUP -->
            <div x-show="tab === 'backup'" x-cloak>
                <h3 class="text-2xl font-semibold text-white mb-6">Copia de Seguridad</h3>
                <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl p-6 sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-blue-500/20 rounded-lg shrink-0">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-white mb-2">Generar Backup Completo</h4>
                            <p class="text-gray-400 mb-4 text-sm leading-relaxed">
                                Esta opción generará un archivo comprimido (<code>.zip</code>) que contendrá:<br>
                                <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-300">
                                    <li>Un volcado completo de la <b>base de datos</b> (registro de usuarios, instrumentos, archivo musical, asistencias, contabilidad...).</li>
                                    <li>Todos los <b>archivos físicos relevantes</b> del sistema (partituras PDF, particellas, imágenes de la banda, carrusel, archivos sonoros...).</li>
                                    <li>Archivo de variables de entorno (configuraciones críticas de conexión).</li>
                                </ul>
                            </p>
                            <p class="text-amber-400 text-sm mb-6 bg-amber-500/10 p-3 rounded-md border border-amber-500/20">
                                ⚠️ <b>Aviso:</b> El proceso puede tardar varios minutos dependiendo de la cantidad de partituras que haya subidas. Por favor, <b>no cierres ni recargues la página</b> hasta que comience la descarga.
                            </p>
                            
                            <form id="backup_form" action="{{ route('admin.settings.backup') }}" method="POST" onsubmit="
                                  const btn = this.querySelector('button'); 
                                  btn.innerHTML = 'Generando copia de seguridad... (Espera por favor)'; 
                                  btn.classList.add('opacity-50', 'cursor-not-allowed'); 
                                  btn.disabled = true;
                                  
                                  document.cookie = 'backup_downloaded=; Max-Age=0; path=/';
                                  let checkCookie = setInterval(() => {
                                      if (document.cookie.includes('backup_downloaded=')) {
                                          clearInterval(checkCookie);
                                          btn.innerHTML = '¡Copia finalizada y descargada!';
                                          btn.classList.remove('bg-blue-600', 'hover:bg-blue-500');
                                          btn.classList.add('bg-green-600', 'hover:bg-green-500');
                                          setTimeout(() => {
                                              btn.innerHTML = 'Generar y Descargar Backup';
                                              btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-green-600', 'hover:bg-green-500');
                                              btn.classList.add('bg-blue-600', 'hover:bg-blue-500');
                                              btn.disabled = false;
                                              document.cookie = 'backup_downloaded=; Max-Age=0; path=/';
                                          }, 5000);
                                      }
                                  }, 1000);
                                  return true;
                              ">
                                @csrf
                                <button type="submit" class="rounded-md bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-600 inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Generar y Descargar Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- CKEditor 4 Full -->
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <script>
        CKEDITOR.on('dialogDefinition', function(ev) {
            if (ev.data.name === 'link') {
                var targetTab = ev.data.definition.getContents('target');
                if (targetTab) targetTab.get('linkTargetType')['default'] = '_blank';
            }
        });
        CKEDITOR.replace('statutes', {
            language: 'es',
            height: 350,
            allowedContent: true,
            versionCheck: false,
            filebrowserImageUploadUrl: '/admin/editor/image-upload?_token={{ csrf_token() }}'
        });
        CKEDITOR.replace('band_history', {
            language: 'es',
            height: 350,
            allowedContent: true,
            versionCheck: false,
            filebrowserImageUploadUrl: '/admin/editor/image-upload?_token={{ csrf_token() }}'
        });
        CKEDITOR.replace('parental_consent_template', {
            language: 'es',
            height: 350,
            allowedContent: true,
            versionCheck: false,
            filebrowserImageUploadUrl: '/admin/editor/image-upload?_token={{ csrf_token() }}'
        });
    </script>
</x-admin-layout>

