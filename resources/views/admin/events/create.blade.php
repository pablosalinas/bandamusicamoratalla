<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Añadir Nuevo Evento</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.events.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-xl">
        <form action="{{ route('admin.events.store') }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre del Evento *</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ej: Ensayo General" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-medium leading-6 text-white">Fecha y Hora *</label>
                        <div class="mt-2">
                            <input type="datetime-local" name="event_date" id="event_date" value="{{ old('event_date') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('event_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium leading-6 text-white">Tipo de Evento *</label>
                        <div class="mt-2">
                            <select id="type" name="type" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="ensayo" {{ old('type') == 'ensayo' ? 'selected' : '' }}>Ensayo</option>
                                <option value="contratada" {{ old('type') == 'contratada' ? 'selected' : '' }}>Contratada</option>
                                <option value="convenio" {{ old('type') == 'convenio' ? 'selected' : '' }}>Convenio</option>
                                <option value="propias" {{ old('type') == 'propias' ? 'selected' : '' }}>Propias</option>
                                <option value="salida" {{ old('type') == 'salida' ? 'selected' : '' }}>Salida</option>
                                <option value="otro" {{ old('type') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        @error('type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium leading-6 text-white">Descripción / Contenido (Opcional)</label>
                        <div class="mt-2">
                            <textarea id="description" name="description" rows="6" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
                        </div>
                        @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center sm:col-span-6">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        <label for="is_active" class="ml-3 block text-sm font-medium leading-6 text-white">Evento Activo (Mostrar en Planning)</label>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Guardar Evento
                </button>
            </div>
        </form>
    </div>

    <!-- CKEditor 5 CDN -->
    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
            background-color: #111827 !important;
            color: #f3f4f6 !important;
            border-bottom-left-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }
        .ck.ck-toolbar {
            background-color: #1f2937 !important;
            border: 1px solid #374151 !important;
            border-top-left-radius: 0.375rem !important;
            border-top-right-radius: 0.375rem !important;
        }
        .ck.ck-editor__main>.ck-editor__editable {
            border: 1px solid #374151 !important;
            border-top: none !important;
        }
        .ck.ck-button {
            color: #d1d5db !important;
        }
        .ck.ck-button:hover, .ck.ck-button.ck-on {
            background-color: #374151 !important;
            color: #fff !important;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/translations/es.js"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#description' ), {
                language: 'es'
            } )
            .catch( error => {
                console.error( error );
            } );
    </script>
</x-admin-layout>
