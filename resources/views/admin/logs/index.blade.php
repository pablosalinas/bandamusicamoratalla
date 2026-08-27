<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Registro de Actividad</h2>
    </x-slot>

    <div class="mt-8 space-y-12">
        
        <!-- Activity Logs -->
        <div>
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h3 class="text-xl font-semibold leading-6 text-white">Actividad de Usuarios</h3>
                    <p class="mt-2 text-sm text-gray-400">Registro de inicios y cierres de sesión de los usuarios.</p>
                </div>
            </div>
            
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-800">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Fecha/Hora</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Usuario</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Acción</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Descripción</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">IP</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800 bg-gray-950">
                                    @forelse($activityLogs as $log)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-300 sm:pl-6">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                                @if($log->user)
                                                    {{ $log->user->name }} {{ $log->user->last_name }}
                                                @else
                                                    <span class="text-gray-500 italic">Desconocido</span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                                @if($log->action === 'login')
                                                    <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20">Login</span>
                                                @elseif($log->action === 'logout')
                                                    <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/20">Logout</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-md bg-amber-400/10 px-2 py-1 text-xs font-medium text-amber-400 ring-1 ring-inset ring-amber-400/20">{{ $log->action }}</span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $log->description }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $log->ip_address }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-4 pl-4 pr-3 text-sm text-center text-gray-500 sm:pl-6">No hay registros de actividad.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $activityLogs->appends(['visits_page' => request('visits_page')])->links() }}
            </div>
        </div>

        <!-- Visits -->
        <div>
            <div class="sm:flex sm:items-center pt-8 border-t border-gray-800">
                <div class="sm:flex-auto">
                    <h3 class="text-xl font-semibold leading-6 text-white">Visitas a la Web Principal</h3>
                    <p class="mt-2 text-sm text-gray-400">Registro de visitas de usuarios anónimos y registrados a las páginas públicas del sitio.</p>
                </div>
            </div>
            
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-800">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Fecha/Hora</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Ruta</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">IP</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Navegador</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800 bg-gray-950">
                                    @forelse($visits as $visit)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-300 sm:pl-6">{{ \Carbon\Carbon::parse($visit->visited_at)->format('d/m/Y H:i:s') }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">/{{ $visit->path }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $visit->ip_address }}</td>
                                            <td class="px-3 py-4 text-sm text-gray-500 truncate max-w-xs" title="{{ $visit->user_agent }}">{{ Str::limit($visit->user_agent, 40) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 pl-4 pr-3 text-sm text-center text-gray-500 sm:pl-6">No hay registros de visitas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $visits->appends(['activity_page' => request('activity_page')])->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>
