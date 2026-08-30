<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Marcas de Instrumentos</h2>
    </x-slot>

    <div class="mt-8 max-w-3xl">
        <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl mb-8">
            <form action="{{ route('admin.instrument-brands.store') }}" method="POST" class="px-4 py-6 sm:p-8">
                @csrf
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Añadir nueva marca</label>
                        <input type="text" name="name" id="name" required placeholder="Ej: Yamaha, Buffet..." class="mt-2 block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                    </div>
                    <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                        Añadir Marca
                    </button>
                </div>
                @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
            </form>
        </div>

        <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                <h3 class="text-base font-semibold leading-6 text-white mb-4">Marcas Registradas</h3>
                
                @if($brands->count() > 0)
                <ul role="list" class="divide-y divide-gray-800 border border-gray-800 rounded-md">
                    @foreach($brands as $brand)
                    <li class="flex items-center justify-between gap-x-6 py-4 px-4 hover:bg-gray-800/50">
                        <div class="min-w-0">
                            <div class="flex items-start gap-x-3">
                                <p class="text-sm font-semibold leading-6 text-white">{{ $brand->name }}</p>
                            </div>
                        </div>
                        <div class="flex flex-none items-center gap-x-4">
                            <form action="{{ route('admin.instrument-brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta marca?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block text-red-600">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-400 italic">No hay marcas registradas.</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
