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

                    @if(auth()->user()->canViewIban())
                    <div class="sm:col-span-6">
                        <label for="band_iban" class="block text-sm font-medium leading-6 text-amber-500">Cuenta Bancaria de la Banda (IBAN)</label>
                        <p class="text-sm text-gray-400 mb-2">Se guardará de forma encriptada en la base de datos y solo será visible para el tesorero.</p>
                        <div class="mt-2">
                            <input type="text" name="band_iban" id="band_iban" value="{{ old('band_iban', $settings['band_iban']) }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-amber-500/50 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" placeholder="ES00 0000 0000 0000 0000 0000">
                        </div>
                        @error('band_iban') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div class="sm:col-span-6">
                        <label for="statutes" class="block text-sm font-medium leading-6 text-white">Estatutos</label>
                        <p class="text-sm text-gray-400 mb-2">Texto de los estatutos. Los usuarios podrán leerlos y descargarlos en PDF.</p>
                        <div class="mt-2 text-black">
                            <textarea id="statutes" name="statutes" rows="10">{{ old('statutes', $settings['statutes'] ?? '') }}</textarea>
                        </div>
                        @error('statutes') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="band_history" class="block text-sm font-medium leading-6 text-white">Historia de la Banda</label>
                        <p class="text-sm text-gray-400 mb-2">Descripción de la historia de la banda de música que se mostrará en la página principal.</p>
                        <div class="mt-2 text-black">
                            <textarea id="band_history" name="band_history" rows="10">{{ old('band_history', $settings['band_history'] ?? '') }}</textarea>
                        </div>
                        @error('band_history') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="carousel_speed" class="block text-sm font-medium leading-6 text-white">Velocidad del Carrusel (Segundos)</label>
                        <p class="text-sm text-gray-400 mb-2">Tiempo que tarda en pasar de una foto a otra.</p>
                        <div class="mt-2">
                            <input type="number" name="carousel_speed" id="carousel_speed" value="{{ old('carousel_speed', $settings['carousel_speed'] ?? 4) }}" required min="1" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('carousel_speed') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
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

    <!-- Carrusel Settings -->
    <div class="mt-8 max-w-3xl">
        <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                <h3 class="text-xl font-bold leading-tight tracking-tight text-white mb-6">Gestión de Medios del Carrusel (Portada)</h3>
                
                <form action="{{ route('admin.settings.carousel.store') }}" method="POST" enctype="multipart/form-data" class="mb-8">
                    @csrf
                    <div class="sm:col-span-6">
                        <label for="media" class="block text-sm font-medium leading-6 text-white">Añadir Fotos o Vídeos</label>
                        <p class="text-sm text-gray-400 mb-2">Selecciona varias fotos o vídeos cortos (MP4) para añadir al carrusel.</p>
                        <div class="mt-2 flex items-center gap-4">
                            <input type="file" name="media[]" id="media" multiple accept="image/*,video/mp4" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                            <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                                Subir
                            </button>
                        </div>
                        @error('media') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        @error('media.*') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>
                </form>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-6">
                    @forelse($carouselMedia as $media)
                        <div class="flex flex-col bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                            <div class="relative group aspect-video flex items-center justify-center bg-gray-900">
                                @if($media->type === 'image')
                                    <img src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover">
                                @else
                                    <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover" muted></video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                        <svg class="w-8 h-8 text-white opacity-70" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                @endif
                                
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form action="{{ route('admin.settings.carousel.destroy', $media) }}" method="POST" onsubmit="return confirm('⚠️ AVISO: Es preferible DESACTIVAR el registro (cambiar su estado a inactivo) en lugar de borrarlo para no perder el historial. ¿Estás completamente seguro de que deseas ELIMINARLO definitivamente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-500 focus:outline-none">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="p-3 border-t border-gray-700">
                                <form action="{{ route('admin.settings.carousel.update', $media) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="description" value="{{ $media->description }}" placeholder="Descripción..." class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                    <button type="submit" class="rounded-md bg-amber-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-amber-500" title="Guardar descripción">
                                        Guardar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-sm text-gray-500 italic">No hay archivos en el carrusel.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#statutes' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
            } )
            .catch( error => { console.error( error ); } );
            
        ClassicEditor
            .create( document.querySelector( '#band_history' ), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
            } )
            .catch( error => { console.error( error ); } );
    </script>
    <style>
        .ck-editor__editable { min-height: 250px; background-color: white !important; color: black !important; }
    </style>
</x-admin-layout>
