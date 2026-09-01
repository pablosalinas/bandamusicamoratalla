<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Acta</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.boards.minutes.index', $board) }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-3xl">
        <form action="{{ route('admin.boards.minutes.update', [$board, $minute]) }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-3">
                        <label for="date" class="block text-sm font-medium leading-6 text-white">Fecha del Acta</label>
                        <div class="mt-2">
                            <input type="date" name="date" id="date" value="{{ old('date', $minute->date->format('Y-m-d')) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium leading-6 text-white">Título</label>
                        <div class="mt-2">
                            <input type="text" name="title" id="title" value="{{ old('title', $minute->title) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('title') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="content" class="block text-sm font-medium leading-6 text-white">Contenido / Redacción del Acta</label>
                        <p class="text-sm text-gray-400 mb-2">Aquí puedes redactar el contenido del acta para que quede registrada en la base de datos y generar un PDF con ella.</p>
                        <div class="mt-2">
                            <textarea name="content" id="content" rows="15" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">{{ old('content', $minute->content) }}</textarea>
                        </div>
                        @error('content') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-800 pt-6 mt-2">
                        <label for="signed_pdf" class="block text-sm font-medium leading-6 text-white">Documento Escaneado Firmado (PDF Opcional)</label>
                        @if($minute->signed_pdf_path)
                            <div class="mb-4">
                                <a href="{{ asset('storage/' . $minute->signed_pdf_path) }}" target="_blank" class="text-amber-500 hover:text-amber-400 font-medium">📄 Ver documento actual</a>
                            </div>
                        @endif
                        <div class="mt-2 flex items-center gap-x-3">
                            <input type="file" name="signed_pdf" id="signed_pdf" accept="application/pdf" class="block w-full text-sm text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sube un nuevo archivo si quieres reemplazar el actual.</p>
                        @error('signed_pdf') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Actualizar Acta
                </button>
            </div>
        </form>
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
            allowedContent: true
        });
    </script>
</x-admin-layout>
