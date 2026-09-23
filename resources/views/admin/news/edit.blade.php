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

                    <div class="relative flex items-start">
                        <div class="flex h-6 items-center">
                            <input id="show_in_hemeroteca" name="show_in_hemeroteca" type="checkbox" value="1" {{ old('show_in_hemeroteca', $news->show_in_hemeroteca ?? true) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/10 bg-gray-800 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="show_in_hemeroteca" class="font-medium text-white">Mostrar en la Hemeroteca</label>
                            <p class="text-gray-400">Permite que esta noticia aparezca indexada y accesible en la Hemeroteca de Noticias.</p>
                        </div>
                    </div>

                    <!-- Panel de Integración con Eventos -->
                    @php
                        $linkedEvent = $news->linked_event;
                    @endphp

                    @if($linkedEvent)
                        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/30">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="p-2 rounded-lg bg-green-500/20 text-green-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-green-400">Noticia vinculada a un Evento</h4>
                                        <p class="text-xs text-gray-300">
                                            Evento: <span class="font-semibold text-white">{{ $linkedEvent->name }}</span> 
                                            ({{ $linkedEvent->event_date->format('d/m/Y H:i') }}) - Tipo: <span class="capitalize text-amber-400 font-semibold">{{ $linkedEvent->type }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('admin.events.edit', $linkedEvent) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-gray-800 text-xs font-semibold text-gray-200 hover:bg-gray-700 border border-gray-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver Evento
                                    </a>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-green-500/20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="relative flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="sync_event" name="sync_event" type="checkbox" value="1" class="h-4 w-4 rounded border-green-500/40 bg-gray-800 text-green-500 focus:ring-green-500 focus:ring-offset-gray-900">
                                    </div>
                                    <div class="ml-2.5 text-xs text-gray-300">
                                        <label for="sync_event" class="font-medium text-white cursor-pointer">Actualizar también el Evento vinculado al guardar cambios</label>
                                    </div>
                                </div>

                                <button type="submit" formaction="{{ route('admin.news.sync-event', $news) }}" formmethod="POST" onclick="return confirm('¿Deseas actualizar el evento vinculado \'{{ addslashes($linkedEvent->name) }}\' con el título, fecha y contenido actuales de esta noticia?');" class="inline-flex items-center gap-1 text-xs text-green-400 hover:text-green-300 hover:underline font-semibold cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Actualizar Evento Ahora
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- No convertida a evento todavía -->
                        <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="p-2 rounded-lg bg-amber-500/20 text-amber-400 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-amber-400">¿Esta noticia también es un Evento?</h4>
                                        <p class="text-xs text-gray-300">Aún no está en el calendario. Puedes convertirla en evento (Tipo: Propio) para no tener que introducirla dos veces.</p>
                                    </div>
                                </div>
                                
                                <button type="submit" formaction="{{ route('admin.news.create-event', $news) }}" formmethod="POST" onclick="return confirm('¿Crear un nuevo Evento (Tipo: Propio) con el título, fecha y contenido de esta noticia?');" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-lg bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-sm transition-all whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Convertir en Evento
                                </button>
                            </div>
                        </div>
                    @endif

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
                            <input type="file" name="image" id="news_image" accept="image/*,video/*" required class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
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

