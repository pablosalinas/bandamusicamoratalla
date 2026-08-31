<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Financiero - {{ $fiscalYear->name }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white;
                color: black;
                font-size: 11pt;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
            @page {
                margin: 1.5cm;
            }
            /* Optimizaciones para imprimir bordes finos y colores */
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid #e5e7eb;
                padding: 0.5rem;
            }
            th {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .text-green-600 { color: #166534 !important; }
            .text-red-600 { color: #991b1b !important; }
            .bg-gray-100 { 
                background-color: #f3f4f6 !important; 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased p-8">

    <!-- Barra de acciones (no se imprime) -->
    <div class="max-w-5xl mx-auto mb-8 flex justify-between items-center no-print">
        <a href="{{ route('admin.fiscal-years.show', $fiscalYear) }}" class="text-gray-600 hover:text-gray-900 font-semibold flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
        <button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Informe
        </button>
    </div>

    <!-- Hoja del Informe -->
    <div class="max-w-5xl mx-auto bg-white p-10 shadow-lg rounded-lg border border-gray-200">
        
        <!-- Cabecera del Informe -->
        <div class="flex justify-between items-start mb-10 border-b-2 border-gray-800 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase">Banda de Música de Moratalla</h1>
                <p class="text-lg text-gray-600 mt-1 font-medium">Informe Financiero y Balance de Ejercicio</p>
                <p class="text-sm text-gray-500 mt-2">Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">{{ $fiscalYear->name }}</h2>
                <p class="text-sm text-gray-600 font-medium">
                    Desde: {{ $fiscalYear->start_date->format('d/m/Y') }}<br>
                    Hasta: {{ $fiscalYear->end_date->format('d/m/Y') }}
                </p>
                <div class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $fiscalYear->is_closed ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $fiscalYear->is_closed ? 'EJERCICIO CERRADO' : 'EJERCICIO ABIERTO' }}
                </div>
            </div>
        </div>

        <!-- Resumen de Balance -->
        <div class="grid grid-cols-3 gap-6 mb-12 avoid-break">
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 shadow-sm text-center">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Ingresos</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($fiscalYear->total_income, 2, ',', '.') }} €</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 shadow-sm text-center">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Gastos</h3>
                <p class="text-2xl font-bold text-red-600">{{ number_format($fiscalYear->total_expense, 2, ',', '.') }} €</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 shadow-sm text-center {{ $fiscalYear->balance >= 0 ? 'ring-2 ring-green-500' : 'ring-2 ring-red-500' }}">
                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wider mb-1">Saldo del Ejercicio</h3>
                <p class="text-3xl font-extrabold {{ $fiscalYear->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($fiscalYear->balance, 2, ',', '.') }} €
                </p>
            </div>
        </div>

        <!-- Detalle de Movimientos -->
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Detalle de Movimientos (Cronológico)</h3>
            
            <table class="w-full text-left text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-900 uppercase font-semibold text-xs border-b border-gray-300">
                    <tr>
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4 w-1/2">Concepto</th>
                        <th class="py-3 px-4 text-center">Tipo</th>
                        <th class="py-3 px-4 text-right">Importe</th>
                        <th class="py-3 px-4 text-center">Punteado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $runningBalance = 0; @endphp
                    @forelse($movements as $mov)
                        @php
                            if ($mov->type === 'income') $runningBalance += $mov->amount;
                            else $runningBalance -= $mov->amount;
                        @endphp
                        <tr class="avoid-break hover:bg-gray-50">
                            <td class="py-3 px-4 whitespace-nowrap">{{ $mov->date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 font-medium">{{ $mov->description }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($mov->type === 'income')
                                    <span class="text-green-600 font-bold uppercase text-xs tracking-wider">Ingreso</span>
                                @else
                                    <span class="text-red-600 font-bold uppercase text-xs tracking-wider">Gasto</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-bold {{ $mov->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $mov->type === 'income' ? '+' : '-' }}{{ number_format($mov->amount, 2, ',', '.') }} €
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($mov->is_reconciled)
                                    <span class="text-green-600 font-bold">✓</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 px-4 text-center text-gray-500 italic">No hay movimientos registrados en este ejercicio.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-800 font-bold">
                    <tr>
                        <td colspan="3" class="py-4 px-4 text-right text-gray-800 uppercase tracking-wider text-xs">Saldo Calculado:</td>
                        <td class="py-4 px-4 text-right text-lg {{ $runningBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($runningBalance, 2, ',', '.') }} €
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-16 text-center text-sm text-gray-500 italic avoid-break">
            <p>Documento oficial generado por la plataforma de gestión de la Banda de Música de Moratalla.</p>
            <p>Este informe refleja los movimientos contabilizados hasta la fecha de generación.</p>
        </div>

    </div>
</body>
</html>
