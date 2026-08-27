<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">
                    Pasar Lista: {{ $event->name }}
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    Fecha: {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }} | Tipo: <span class="capitalize">{{ $event->type }}</span>
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.events.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-4xl" x-data="attendanceForm()">
        <div class="mb-4 flex flex-wrap gap-2">
            <button type="button" @click="markAll('present')" class="rounded-md bg-green-500/20 px-3 py-2 text-sm font-semibold text-green-400 hover:bg-green-500/30 ring-1 ring-inset ring-green-500/30">
                Marcar todos Presentes
            </button>
            <button type="button" @click="markAll('absent')" class="rounded-md bg-red-500/20 px-3 py-2 text-sm font-semibold text-red-400 hover:bg-red-500/30 ring-1 ring-inset ring-red-500/30">
                Marcar todos Ausentes
            </button>
            <button type="button" @click="markAll('excused')" class="rounded-md bg-yellow-500/20 px-3 py-2 text-sm font-semibold text-yellow-400 hover:bg-yellow-500/30 ring-1 ring-inset ring-yellow-500/30">
                Marcar todos Justificados
            </button>
        </div>

        <form action="{{ route('admin.events.attendance.store', $event) }}" method="POST" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            
            <div class="px-4 py-6 sm:p-8">
                
                <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Músico</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white w-1/4">Presente</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white w-1/4">Falta</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white w-1/4">Falta Justificada</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @foreach ($users as $user)
                                @php
                                    $currentStatus = $attendances->has($user->id) ? $attendances[$user->id] : 'present';
                                @endphp
                                <tr class="hover:bg-gray-900/50 transition-colors">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                        {{ $user->name }} {{ $user->last_name }}
                                        <div class="text-xs text-gray-500 font-normal">
                                            {{ $user->instruments->pluck('name')->implode(', ') }}
                                        </div>
                                    </td>
                                    
                                    <td class="whitespace-nowrap px-3 py-4 text-center">
                                        <div class="flex justify-center">
                                            <input type="radio" name="attendance[{{ $user->id }}]" value="present" 
                                                class="h-6 w-6 border-gray-700 bg-gray-900 text-green-600 focus:ring-green-600 focus:ring-offset-gray-900 cursor-pointer status-radio present-radio"
                                                {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    
                                    <td class="whitespace-nowrap px-3 py-4 text-center bg-red-900/10">
                                        <div class="flex justify-center">
                                            <input type="radio" name="attendance[{{ $user->id }}]" value="absent" 
                                                class="h-6 w-6 border-gray-700 bg-gray-900 text-red-600 focus:ring-red-600 focus:ring-offset-gray-900 cursor-pointer status-radio absent-radio"
                                                {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    
                                    <td class="whitespace-nowrap px-3 py-4 text-center bg-yellow-900/10">
                                        <div class="flex justify-center">
                                            <input type="radio" name="attendance[{{ $user->id }}]" value="excused" 
                                                class="h-6 w-6 border-gray-700 bg-gray-900 text-yellow-600 focus:ring-yellow-600 focus:ring-offset-gray-900 cursor-pointer status-radio excused-radio"
                                                {{ $currentStatus === 'excused' ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8 bg-gray-950 rounded-b-xl sticky bottom-0">
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 w-full sm:w-auto">
                    Guardar Asistencia
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attendanceForm', () => ({
                markAll(status) {
                    const radios = document.querySelectorAll('.status-radio.' + status + '-radio');
                    radios.forEach(radio => {
                        radio.checked = true;
                    });
                }
            }))
        })
    </script>
</x-admin-layout>
