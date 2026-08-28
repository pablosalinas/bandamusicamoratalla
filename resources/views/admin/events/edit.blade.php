<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Editar Evento: {{ $event->name }}</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.events.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-xl">
        <form action="{{ route('admin.events.update', $event) }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre del Evento *</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-medium leading-6 text-white">Fecha y Hora *</label>
                        <div class="mt-2">
                            <input type="datetime-local" name="event_date" id="event_date" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('event_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium leading-6 text-white">Tipo de Evento *</label>
                        <div class="mt-2">
                            <select id="type" name="type" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="ensayo" {{ old('type', $event->type) == 'ensayo' ? 'selected' : '' }}>Ensayo</option>
                                <option value="concierto" {{ old('type', $event->type) == 'concierto' ? 'selected' : '' }}>Concierto</option>
                                <option value="oficial" {{ old('type', $event->type) == 'oficial' ? 'selected' : '' }}>Acto Oficial (Procesión, Pasacalles, etc.)</option>
                                <option value="otro" {{ old('type', $event->type) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        @error('type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold leading-6 text-white">Cancelar</a>
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Actualizar Evento
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
