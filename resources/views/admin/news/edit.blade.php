<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Publicación</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.news.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-2xl">
        <form action="{{ route('admin.news.update', $news) }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                    
                    <div>
                        <label for="title" class="block text-sm font-medium leading-6 text-white">Título *</label>
                        <div class="mt-2">
                            <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('title') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-medium leading-6 text-white">Fecha del Evento (Opcional)</label>
                        <div class="mt-2">
                            <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $news->event_date ? $news->event_date->format('Y-m-d') : '') }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('event_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="active_from" class="block text-sm font-medium leading-6 text-white">Visible desde (Opcional)</label>
                            <div class="mt-2">
                                <input type="date" name="active_from" id="active_from" value="{{ old('active_from', $news->active_from ? $news->active_from->format('Y-m-d') : '') }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            @error('active_from') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="active_to" class="block text-sm font-medium leading-6 text-white">Visible hasta (Opcional)</label>
                            <div class="mt-2">
                                <input type="date" name="active_to" id="active_to" value="{{ old('active_to', $news->active_to ? $news->active_to->format('Y-m-d') : '') }}" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                            </div>
                            @error('active_to') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium leading-6 text-white">Contenido *</label>
                        <div class="mt-2">
                            <textarea id="content" name="content" rows="6" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">{{ old('content', $news->content) }}</textarea>
                        </div>
                        @error('content') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="relative flex items-start">
                        <div class="flex h-6 items-center">
                            <input id="is_published" name="is_published" type="checkbox" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/10 bg-gray-800 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="is_published" class="font-medium text-white">Publicada</label>
                            <p class="text-gray-400">Desmarca esta opción si quieres guardarla como borrador para revisarla después y que nadie la vea.</p>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- News Images Section -->
    <div class="mt-8 max-w-2xl">
        <div class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            <div class="px-4 py-6 sm:p-8">
                <h3 class="text-xl font-bold leading-tight tracking-tight text-white mb-6">Imágenes de la Noticia</h3>
                <p class="text-sm text-gray-400 mb-6">La imagen con el <b>Orden</b> más bajo será la portada de la noticia. El resto formarán un carrusel que se mostrará al ver el detalle.</p>
                
                <form action="{{ route('admin.news.images.store', $news) }}" method="POST" enctype="multipart/form-data" class="mb-8">
                    @csrf
                    <div class="sm:col-span-6">
                        <label for="news_image" class="block text-sm font-medium leading-6 text-white">Subir nueva imagen</label>
                        <div class="mt-2 flex items-center gap-4">
                            <input type="file" name="image" id="news_image" accept="image/*" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                            <button type="submit" class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                                Añadir Imagen
                            </button>
                        </div>
                        @error('image') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                    @forelse($news->newsImages as $image)
                        <div class="flex flex-col bg-gray-800 rounded-lg overflow-hidden shadow-sm border border-gray-700">
                            <div class="relative group aspect-video">
                                <img src="{{ $image->url }}" class="w-full h-full object-cover">
                                
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form action="{{ route('admin.news.images.destroy', $image) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta imagen de forma definitiva?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-600 text-white rounded-full hover:bg-red-500 focus:outline-none">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                                @if($loop->first)
                                    <div class="absolute top-2 left-2 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded shadow">
                                        IMAGEN PRINCIPAL
                                    </div>
                                @endif
                            </div>
                            <div class="p-3 border-t border-gray-700">
                                <form action="{{ route('admin.news.images.update', $image) }}" method="POST" class="flex flex-col gap-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-gray-400 w-12">Orden:</label>
                                        <input type="number" name="sort_order" value="{{ $image->sort_order }}" class="block w-16 rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs text-center" title="Orden de aparición (menor = primero)">
                                    </div>
                                    <input type="text" name="description" value="{{ $image->description }}" placeholder="Descripción de la imagen..." class="block w-full rounded-md border-0 bg-gray-900 py-1 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-xs">
                                    <button type="submit" class="rounded-md bg-amber-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 mt-1">
                                        Guardar cambios
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-sm text-gray-500 italic">No hay imágenes en esta noticia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- CKEditor 4 Full (fuente HTML + enlaces en nueva pestaña) -->
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <script>
        CKEDITOR.on('dialogDefinition', function(ev) {
            if (ev.data.name === 'link') {
                var targetTab = ev.data.definition.getContents('target');
                if (targetTab) targetTab.get('linkTargetType')['default'] = '_blank';
            }
        });
        CKEDITOR.replace('content', {
            language: 'es',
            height: 450,
            allowedContent: true,
            versionCheck: false,
            filebrowserImageUploadUrl: '/admin/editor/image-upload?_token={{ csrf_token() }}'
        });
    </script>
</x-admin-layout>

