<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Nuevo Ejercicio Económico</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.fiscal-years.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-2xl">
        <form action="{{ route('admin.fiscal-years.store') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-6">
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre del Ejercicio (Ej: Presupuesto 2024-2025)</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="start_date" class="block text-sm font-medium leading-6 text-white">Fecha de Inicio</label>
                        <div class="mt-2">
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;" onchange="updateEndDate()">
                        </div>
                        @error('start_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="end_date" class="block text-sm font-medium leading-6 text-white">Fecha de Fin (Propuesta a 1 año)</label>
                        <div class="mt-2">
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('end_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Crear Ejercicio
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateEndDate() {
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');
            if (startInput.value && !endInput.value) {
                const date = new Date(startInput.value);
                date.setFullYear(date.getFullYear() + 1);
                date.setDate(date.getDate() - 1);
                endInput.value = date.toISOString().split('T')[0];
            }
        }
    </script>
</x-admin-layout>
