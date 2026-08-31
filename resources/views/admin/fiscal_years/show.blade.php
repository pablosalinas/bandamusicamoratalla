<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">
                    Ejercicio: {{ $fiscalYear->name }}
                    @if($fiscalYear->is_closed)
                        <span class="ml-3 inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-sm font-medium text-red-400 ring-1 ring-inset ring-red-400/30">Cerrado</span>
                    @endif
                </h2>
                <p class="mt-2 text-sm text-gray-400">{{ $fiscalYear->start_date->format('d/m/Y') }} - {{ $fiscalYear->end_date->format('d/m/Y') }}</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex space-x-3">
                <a href="{{ route('admin.fiscal-years.report', $fiscalYear) }}" target="_blank" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Generar Informe
                </a>
                <a href="{{ route('admin.fiscal-years.index') }}" class="block rounded-md bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-700">
                    Volver
                </a>
                @if(!$fiscalYear->is_closed)
                <a href="{{ route('admin.fiscal-years.budget-movements.create', $fiscalYear) }}" class="block rounded-md bg-amber-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                    Añadir Movimiento
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Resumen -->
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-gray-900 px-4 py-5 shadow sm:p-6 ring-1 ring-white/10 border-l-4 border-green-500">
            <dt class="truncate text-sm font-medium text-gray-400 flex items-center justify-between">
                Total Ingresos
                <span class="text-xs text-gray-500">Año anterior: {{ isset($previousYear) ? number_format($previousYear->total_income, 2, ',', '.') : '0,00' }} €</span>
            </dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-400">{{ number_format($fiscalYear->total_income, 2, ',', '.') }} €</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-gray-900 px-4 py-5 shadow sm:p-6 ring-1 ring-white/10 border-l-4 border-red-500">
            <dt class="truncate text-sm font-medium text-gray-400 flex items-center justify-between">
                Total Gastos
                <span class="text-xs text-gray-500">Año anterior: {{ isset($previousYear) ? number_format($previousYear->total_expense, 2, ',', '.') : '0,00' }} €</span>
            </dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-red-400">{{ number_format($fiscalYear->total_expense, 2, ',', '.') }} €</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-gray-900 px-4 py-5 shadow sm:p-6 ring-1 ring-white/10 border-l-4 {{ $fiscalYear->balance >= 0 ? 'border-amber-500' : 'border-red-500' }}">
            <dt class="truncate text-sm font-medium text-gray-400 flex items-center justify-between">
                Saldo del Ejercicio
                <span class="text-xs text-gray-500">Año anterior: {{ isset($previousYear) ? number_format($previousYear->balance, 2, ',', '.') : '0,00' }} €</span>
            </dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight {{ $fiscalYear->balance >= 0 ? 'text-amber-500' : 'text-red-400' }}">{{ number_format($fiscalYear->balance, 2, ',', '.') }} €</dd>
        </div>
    </div>

    <!-- Comparativa Gráfica -->
    <div class="mt-8 bg-gray-900 rounded-lg shadow ring-1 ring-white/10 p-6">
        <div class="flex items-center justify-between mb-4 border-b border-gray-800 pb-4">
            <h3 class="text-lg font-medium text-white">Gráficos Comparativos</h3>
            <form method="GET" action="{{ route('admin.fiscal-years.show', $fiscalYear) }}" class="flex items-center space-x-3">
                <input type="hidden" name="sort" value="{{ $sortBy }}">
                <label for="compare_years" class="text-sm font-medium text-gray-300">Años hacia atrás a comparar:</label>
                <input type="number" name="compare_years" id="compare_years" min="0" max="10" value="{{ $compareYearsCount }}" class="block w-20 rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6 text-center">
                <button type="submit" class="rounded-md bg-amber-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">Comparar</button>
            </form>
        </div>

        @if($compareYearsCount > 0 && !empty($comparativeData))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                <!-- Gráfico de Pastel Concéntrico -->
                <div class="bg-gray-800 p-4 rounded-lg flex flex-col items-center justify-center">
                    <h4 class="text-center text-gray-300 font-semibold mb-2">Proporción Ingresos/Gastos/Saldo</h4>
                    <div class="relative w-full aspect-square max-h-[300px] flex justify-center">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
                
                <!-- Gráfico de Columnas -->
                <div class="bg-gray-800 p-4 rounded-lg flex flex-col items-center justify-center">
                    <h4 class="text-center text-gray-300 font-semibold mb-2">Evolución de Totales</h4>
                    <div class="relative w-full aspect-[4/3] max-h-[300px] flex justify-center">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const data = @json($comparativeData);
                    
                    // Colores temáticos
                    const colorIncome = 'rgba(74, 222, 128, 0.8)'; // green-400
                    const colorExpense = 'rgba(248, 113, 113, 0.8)'; // red-400
                    const colorBalancePos = 'rgba(251, 191, 36, 0.8)'; // amber-400
                    const colorBalanceNeg = 'rgba(153, 27, 27, 0.8)'; // red-800

                    const borderIncome = 'rgba(34, 197, 94, 1)'; 
                    const borderExpense = 'rgba(239, 68, 68, 1)';
                    const borderBalance = 'rgba(245, 158, 11, 1)';

                    // Preparar datos para Doughnut (cada año es un dataset concéntrico)
                    const doughnutDatasets = data.labels.map((year, index) => {
                        return {
                            label: year,
                            data: [data.income[index], data.expense[index], Math.abs(data.balance[index])],
                            backgroundColor: [
                                colorIncome, 
                                colorExpense, 
                                data.balance[index] >= 0 ? colorBalancePos : colorBalanceNeg
                            ],
                            borderColor: '#1f2937', // gray-800
                            borderWidth: 2
                        };
                    });

                    new Chart(document.getElementById('doughnutChart'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Ingresos', 'Gastos', 'Saldo'],
                            datasets: doughnutDatasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: '#d1d5db' } // gray-300
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed !== null) {
                                                label += new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(context.parsed);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Preparar datos para Bar Chart (agrupado por año)
                    new Chart(document.getElementById('barChart'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Ingresos',
                                    data: data.income,
                                    backgroundColor: colorIncome,
                                    borderColor: borderIncome,
                                    borderWidth: 1
                                },
                                {
                                    label: 'Gastos',
                                    data: data.expense,
                                    backgroundColor: colorExpense,
                                    borderColor: borderExpense,
                                    borderWidth: 1
                                },
                                {
                                    label: 'Saldo',
                                    data: data.balance,
                                    backgroundColor: data.balance.map(b => b >= 0 ? colorBalancePos : colorBalanceNeg),
                                    borderColor: borderBalance,
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                    ticks: { color: '#9ca3af' } // gray-400
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#9ca3af' }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: '#d1d5db' }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed.y !== null) {
                                                label += new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(context.parsed.y);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @elseif($compareYearsCount > 0)
            <div class="mt-4 text-center text-sm text-gray-500 italic">No hay suficientes ejercicios anteriores para comparar.</div>
        @endif
    </div>

    <!-- Filtros de ordenación -->
    <div class="mt-8 flex justify-end">
        <form method="GET" action="{{ route('admin.fiscal-years.show', $fiscalYear) }}" class="flex items-center space-x-3">
            <label for="sort" class="text-sm font-medium text-gray-300">Ordenar por:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" class="block w-40 rounded-md border-0 bg-gray-800 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
                <option value="date" {{ $sortBy === 'date' ? 'selected' : '' }}>Fecha</option>
                <option value="type" {{ $sortBy === 'type' ? 'selected' : '' }}>Tipo (Ingresos/Gastos)</option>
            </select>
        </form>
    </div>

    <!-- Tabla Movimientos -->
    <div class="mt-4 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Fecha</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Concepto</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Tipo</th>
                                <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-white">Importe</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-white">Punteo / Doc</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 bg-gray-950">
                            @forelse ($movements as $mov)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-300 sm:pl-6">
                                        {{ $mov->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-white">
                                        {{ $mov->description }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($mov->type === 'income')
                                            <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Ingreso</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/30">Gasto</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-right {{ $mov->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $mov->type === 'income' ? '+' : '-' }}{{ number_format($mov->amount, 2, ',', '.') }} €
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center flex flex-col items-center justify-center space-y-1">
                                        @if($mov->is_reconciled)
                                            <span title="Punteado (Acreditado)" class="text-green-400">
                                                <svg class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                        @else
                                            <span title="No Punteado" class="text-gray-500">
                                                <svg class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </span>
                                        @endif
                                        
                                        @if($mov->document_url)
                                            <a href="{{ $mov->document_url }}" target="_blank" class="text-amber-500 hover:text-amber-400 text-xs mt-1 underline">Ver Doc</a>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        @if(!$fiscalYear->is_closed)
                                            <a href="{{ route('admin.fiscal-years.budget-movements.edit', [$fiscalYear, $mov]) }}" class="text-amber-500 hover:text-amber-400 mr-4">Editar</a>
                                            
                                            @if(!$mov->is_reconciled)
                                            <form action="{{ route('admin.fiscal-years.budget-movements.destroy', [$fiscalYear, $mov]) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este movimiento?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400">Eliminar</button>
                                            </form>
                                            @else
                                            <span title="Quita el punteo para poder borrar" class="text-gray-600 cursor-not-allowed line-through">Eliminar</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-sm text-gray-400 text-center">No hay movimientos en este ejercicio.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $movements->appends(['sort' => $sortBy])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
