<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl sm:text-3xl font-bold leading-tight tracking-tight text-white">Archivo Sonoro</h2>
        </div>
    </x-slot>

    <!-- Upload Form -->
    <div class="mt-8 max-w-4xl">
        <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                <h3 class="text-xl font-bold leading-tight tracking-tight text-white mb-6">Añadir Nuevo Archivo (Audio o Vídeo)</h3>
                
                <form action="{{ route('admin.media-archive.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <label for="title" class="block text-sm font-medium leading-6 text-white">Título</label>
                            <div class="mt-2">
                                <input type="text" name="title" id="title" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            @error('title') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="type" class="block text-sm font-medium leading-6 text-white">Tipo de Archivo</label>
                            <div class="mt-2">
                                <select name="type" id="type" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                    <option value="audio">Audio</option>
                                    <option value="video">Vídeo</option>
                                </select>
                            </div>
                            @error('type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="composer" class="block text-sm font-medium leading-6 text-white">Compositor (Opcional)</label>
                            <div class="mt-2">
                                <input type="text" name="composer" id="composer" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            @error('composer') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="music_type" class="block text-sm font-medium leading-6 text-white">Tipo de Música (Opcional)</label>
                            <div class="mt-2">
                                <input list="music_types" name="music_type" id="music_type" placeholder="Selecciona o escribe..." class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <datalist id="music_types">
                                    @foreach($existingTypes as $type)
                                        <option value="{{ $type }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            @error('music_type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="performance_date" class="block text-sm font-medium leading-6 text-white">Fecha de Interpretación (Opcional)</label>
                            <div class="mt-2">
                                <input type="date" name="performance_date" id="performance_date" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                            </div>
                            @error('performance_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium leading-6 text-white">Descripción (Opcional)</label>
                            <div class="mt-2">
                                <textarea id="description" name="description" rows="2" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6"></textarea>
                            </div>
                            @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="file" class="block text-sm font-medium leading-6 text-white">Archivo Multimedia</label>
                            <p class="text-sm text-gray-400 mb-2">Audio o Vídeo (Max 20MB).</p>
                            <div class="mt-2">
                                <input type="file" name="file" id="file" required accept="audio/*,video/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                            </div>
                            @error('file') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="images" class="block text-sm font-medium leading-6 text-white">Carrusel de Imágenes (Opcional)</label>
                            <p class="text-sm text-gray-400 mb-2">Puedes añadir varias fotos.</p>
                            <div class="mt-2">
                                <input type="file" name="images[]" id="images" multiple accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                            </div>
                            @error('images') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            @error('images.*') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="sm:col-span-2">
                            <div class="flex items-center gap-x-3">
                                <input id="is_active" name="is_active" type="checkbox" checked class="h-4 w-4 rounded border-white/10 bg-gray-800 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                                <label for="is_active" class="text-sm font-medium leading-6 text-white">Activo (Visible en la web)</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-800">
                        <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                            Subir Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Media List -->
    <div class="mt-12 max-w-7xl">
        <h3 class="text-xl font-bold leading-tight tracking-tight text-white mb-6">Archivos Subidos</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($mediaArchives as $media)
                <div class="flex flex-col bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                    <div class="relative aspect-video flex items-center justify-center bg-gray-900">
                        @if($media->type === 'video')
                            <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover" controls></video>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                <svg class="w-12 h-12 text-amber-500/80 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                            </div>
                            <audio src="{{ asset('storage/' . $media->file_path) }}" class="absolute bottom-0 w-full h-10" controls></audio>
                        @endif
                    </div>
                    
                    <div class="p-4 flex-1 flex flex-col">
                        <form action="{{ route('admin.media-archive.update', $media) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-3">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <input type="text" name="title" value="{{ $media->title }}" required class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm font-semibold" placeholder="Título">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="text" name="composer" value="{{ $media->composer }}" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs" placeholder="Compositor">
                                </div>
                                <div>
                                    <input list="music_types" name="music_type" value="{{ $media->music_type }}" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs" placeholder="Tipo de música">
                                </div>
                            </div>
                            
                            <div>
                                <input type="date" name="performance_date" value="{{ $media->performance_date ? $media->performance_date->format('Y-m-d') : '' }}" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs" style="color-scheme: dark;">
                            </div>
                            
                            <div>
                                <textarea name="description" rows="2" class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs" placeholder="Descripción...">{{ $media->description }}</textarea>
                            </div>

                            <div class="mt-2 border-t border-gray-700 pt-2">
                                <label class="block text-xs text-gray-400 mb-1">Añadir más imágenes al carrusel</label>
                                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-xs text-gray-400 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                            </div>
                            
                            <div class="flex items-center justify-between mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ $media->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-white/10 bg-gray-800 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                                    <span class="text-xs text-gray-300">Activo</span>
                                </label>
                                
                                <button type="submit" class="text-xs font-semibold text-amber-500 hover:text-amber-400">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between items-center">
                            <form action="{{ route('admin.media-archive.update-order', $media) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="sort_order" value="{{ $media->sort_order }}" class="w-16 rounded-md border-0 bg-gray-900 py-1 text-center text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                <button type="submit" class="text-gray-400 hover:text-white" title="Actualizar orden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.media-archive.destroy', $media) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este archivo y todas sus imágenes?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400" title="Eliminar archivo">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        
                        @if($media->images->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-700">
                                <h4 class="text-xs font-semibold text-gray-400 mb-2">Imágenes del Carrusel</h4>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($media->images as $image)
                                        <div class="relative group aspect-square bg-gray-900 rounded overflow-hidden">
                                            <img src="{{ asset('storage/' . $image->file_path) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <form action="{{ route('admin.media-archive.images.destroy', $image) }}" method="POST" onsubmit="return confirm('¿Eliminar esta imagen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    No hay archivos en el Archivo Sonoro.
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
