<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Configuración del Sitio</h2>
    </x-slot>

    <div class="mt-8 max-w-3xl">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-6">
                        <label for="band_name" class="block text-sm font-medium leading-6 text-white">Nombre de la Banda</label>
                        <p class="text-sm text-gray-400 mb-2">Este nombre se mostrará en la página principal y en el panel.</p>
                        <div class="mt-2">
                            <input type="text" name="band_name" id="band_name" value="{{ old('band_name', $settings['band_name']) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('band_name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="session_timeout" class="block text-sm font-medium leading-6 text-white">Tiempo de sesión (Minutos)</label>
                        <p class="text-sm text-gray-400 mb-2">Tiempo de inactividad antes de cerrar sesión automáticamente.</p>
                        <div class="mt-2">
                            <input type="number" name="session_timeout" id="session_timeout" value="{{ old('session_timeout', $settings['session_timeout']) }}" required min="1" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('session_timeout') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="statutes" class="block text-sm font-medium leading-6 text-white">Estatutos</label>
                        <div class="mt-2 text-black bg-white rounded-md p-1">
                            <input id="statutes" type="hidden" name="statutes" value="{{ old('statutes', $settings['statutes'] ?? '') }}">
                            <trix-editor input="statutes" class="trix-content min-h-[300px]"></trix-editor>
                        </div>
                        @error('statutes') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="band_history" class="block text-sm font-medium leading-6 text-white">Historia de la Banda</label>
                        <div class="mt-2 text-black bg-white rounded-md p-1">
                            <input id="band_history" type="hidden" name="band_history" value="{{ old('band_history', $settings['band_history'] ?? '') }}">
                            <trix-editor input="band_history" class="trix-content min-h-[300px]"></trix-editor>
                        </div>
                        @error('band_history') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>

    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        trix-editor { min-height: 250px; background: white; }
        trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
    </style>
</x-admin-layout>
