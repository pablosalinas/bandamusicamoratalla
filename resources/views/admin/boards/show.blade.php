<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Miembros de la Junta Directiva</h2>
                <p class="mt-2 text-sm text-gray-400">
                    Legislatura: {{ $board->start_date->format('d/m/Y') }} - {{ $board->end_date ? $board->end_date->format('d/m/Y') : 'Actualidad' }}
                    @if($board->is_active)
                        <span class="ml-2 inline-flex items-center rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-500/20">Activa</span>
                    @endif
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.boards.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        
        <!-- Formulario para añadir miembro -->
        <div class="lg:col-span-1">
            <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-base font-semibold leading-6 text-white mb-4">Añadir Miembro a la Junta</h3>
                    
                    <form action="{{ route('admin.boards.members.add', $board) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium leading-6 text-white">Músico / Usuario</label>
                                <select id="user_id" name="user_id" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                    <option value="">Selecciona un usuario...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="role_name" class="block text-sm font-medium leading-6 text-white">Cargo (Ej: Presidente, Tesorero, Vocal)</label>
                                <input type="text" name="role_name" id="role_name" value="{{ old('role_name') }}" required class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                @error('role_name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                                    Añadir Cargo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de miembros actuales -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-950">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Cargo</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Nombre del Usuario</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 bg-gray-900">
                        @forelse ($board->members as $member)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-amber-500 sm:pl-6">
                                    {{ $member->role_name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ $member->user->name }}
                                    <span class="text-gray-500 block text-xs">{{ $member->user->email }}</span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <form action="{{ route('admin.boards.members.remove', [$board, $member]) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que quieres quitar a esta persona de la junta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-8 text-sm text-gray-400 text-center">
                                    Aún no hay ningún miembro asignado a esta legislatura.<br>
                                    Usa el formulario de la izquierda para empezar a añadir cargos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>
