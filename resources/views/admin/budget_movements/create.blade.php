<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Añadir Movimiento a {{ $fiscalYear->name }}</h2>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.fiscal-years.show', $fiscalYear) }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 max-w-2xl">
        <form action="{{ route('admin.fiscal-years.budget-movements.store', $fiscalYear) }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 shadow-sm ring-1 ring-gray-800 sm:rounded-xl">
            @csrf
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    
                    <div class="sm:col-span-3">
                        <label for="date" class="block text-sm font-medium leading-6 text-white">Fecha</label>
                        <div class="mt-2">
                            <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" style="color-scheme: dark;">
                        </div>
                        @error('date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="type" class="block text-sm font-medium leading-6 text-white">Tipo de Movimiento</label>
                        <div class="mt-2">
                            <select id="type" name="type" class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Gasto</option>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Ingreso</option>
                            </select>
                        </div>
                        @error('type') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium leading-6 text-white">Concepto / Descripción</label>
                        <div class="mt-2">
                            <input type="text" name="description" id="description" value="{{ old('description') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" placeholder="Ej: Compra de atriles">
                        </div>
                        @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="amount" class="block text-sm font-medium leading-6 text-white">Importe (€)</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" required class="block w-full rounded-md border-0 bg-gray-800 py-1.5 pr-10 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6" placeholder="0.00">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-400 sm:text-sm">€</span>
                            </div>
                        </div>
                        @error('amount') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="document" class="block text-sm font-medium leading-6 text-white">Documento Acreditativo (Ticket, Factura, Recibo...)</label>
                        <div class="mt-2">
                            <input type="file" name="document" id="document" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-amber-500 hover:file:bg-gray-700" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Opcional. Formatos aceptados: PDF, JPG, PNG (Max 5MB)</p>
                        @error('document') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6 flex items-center mt-2">
                        <input id="is_reconciled" name="is_reconciled" type="checkbox" value="1" {{ old('is_reconciled') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-amber-600 focus:ring-amber-600 focus:ring-offset-gray-900">
                        <label for="is_reconciled" class="ml-3 block text-sm font-medium leading-6 text-white">Marcar como Punteado / Acreditado</label>
                    </div>

                </div>
            </div>
            
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-800 px-4 py-4 sm:px-8">
                <button type="submit" class="rounded-md bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600">
                    Guardar Movimiento
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
