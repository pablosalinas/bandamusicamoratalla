<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Legislatura</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.boards.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-xl">
        <form action="{{ route('admin.boards.update', $board) }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                    
                    <div>
                        <label for="start_date" class="block text-sm font-medium leading-6 text-white">Fecha de Inicio *</label>
                        <div class="mt-2">
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $board->start_date ? $board->start_date->format('Y-m-d') : '') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('start_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium leading-6 text-white">Fecha de Fin (Opcional)</label>
                        <div class="mt-2">
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $board->end_date ? $board->end_date->format('Y-m-d') : '') }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        <p class="mt-1 text-sm text-gray-400">Si está vacía, se considera la legislatura actual o indefinida.</p>
                        @error('end_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-6 items-center">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $board->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/10 bg-gray-800 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="is_active" class="font-medium text-white">Legislatura Activa</label>
                            <p class="text-gray-400">Si marcas esto, será la junta directiva que se mostrará actualmente (desactivará otras).</p>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.boards.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
