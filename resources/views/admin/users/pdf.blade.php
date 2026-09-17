<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Músicos y Miembros</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #fff;
            color: #111827;
            padding: 12px;
            font-size: 11px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 28%;
            opacity: 0.08;
            z-index: -1;
            pointer-events: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #D97706;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header img {
            max-height: 55px;
        }
        .header-text {
            text-align: right;
        }
        .header-text h2 {
            margin: 0;
            font-size: 14px;
            color: #4B5563;
            font-weight: 600;
        }
        .header-text h1 {
            color: #D97706;
            margin: 2px 0 0 0;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            line-height: 1.15;
        }
        th {
            background-color: #F3F4F6;
            color: #1F2937;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5px;
            padding: 4px 5px;
            border: 1px solid #D1D5DB;
            letter-spacing: 0.03em;
        }
        td {
            padding: 3px 5px;
            border: 1px solid #E5E7EB;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 0.8cm;
            }
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                color: #000;
                font-size: 9px;
            }
            table {
                font-size: 8.5px;
            }
            th, td {
                padding: 2.5px 4px;
            }
            .watermark {
                width: 25%;
                opacity: 0.06;
            }
        }
    </style>
</head>
<body>
    @php
        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $logos = [];
        foreach ($rawLogos as $logo) {
            if (is_string($logo)) $logos[] = ['path' => $logo, 'order' => 999];
            else if (is_array($logo)) $logos[] = $logo;
        }
        usort($logos, function($a, $b) { return ($a['order'] ?? 999) <=> ($b['order'] ?? 999); });
        $logos = array_column($logos, 'path');
        $primaryLogo = count($logos) > 0 ? $logos[0] : 'images/logo.jpg';
        $logoSrc = str_starts_with($primaryLogo, 'images/') ? asset($primaryLogo) : asset('storage/' . $primaryLogo);
        $bandName = \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música');

        $statusTitles = [
            'all' => 'Listado General de Músicos y Miembros',
            'active' => 'Listado de Músicos en ALTA (Activos)',
            'inactive' => 'Listado de Músicos en BAJA / Inactivos',
            'pending' => 'Listado de Músicos PENDIENTES DE VALIDACIÓN'
        ];
        $reportTitle = $statusTitles[$status] ?? 'Listado de Músicos';
    @endphp

    <img src="{{ $logoSrc }}" class="watermark" alt="Marca de agua">

    <!-- Barra de acciones en pantalla -->
    <div class="mb-4 flex justify-between items-center no-print bg-gray-100 p-2.5 rounded-lg border border-gray-300">
        <div class="text-xs text-gray-700">
            <strong>Filtro aplicado:</strong> {{ $reportTitle }} (Total: <strong>{{ $users->count() }}</strong> miembros)
            @if(!empty($search))
                | Búsqueda: <em>"{{ $search }}"</em>
            @endif
        </div>
        <div class="flex gap-2">
            <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">Cerrar</button>
            <a href="{{ route('admin.users.export.csv', ['status' => $status, 'search' => $search]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition flex items-center gap-1">
                Descargar Excel / CSV
            </a>
            <button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition flex items-center gap-1">
                Imprimir / Guardar en PDF
            </button>
        </div>
    </div>

    <!-- Encabezado oficial -->
    <div class="header">
        <div class="flex items-center gap-3">
            <img src="{{ $logoSrc }}" alt="Logo">
            <div>
                <h2 class="text-left">{{ $bandName }}</h2>
                <div class="text-[10px] text-gray-500 font-medium">Registro General de Miembros y Músicos</div>
            </div>
        </div>
        <div class="header-text">
            <h1>{{ $reportTitle }}</h1>
            <div class="text-[10px] text-gray-500">
                Emitido el {{ now()->format('d/m/Y H:i') }} | Total: {{ $users->count() }} registros
            </div>
        </div>
    </div>

    <!-- Tabla Compacta de Máxima Información -->
    <table>
        <thead>
            <tr>
                <th style="text-align: left;">Apellidos y Nombre</th>
                <th style="width: 75px; text-align: center;">NIF / NIE</th>
                <th style="width: 65px; text-align: center;">Nacimiento</th>
                <th style="width: 35px; text-align: center;">Edad</th>
                <th style="text-align: left;">Email</th>
                <th style="width: 75px; text-align: left;">Teléfono</th>
                <th style="text-align: left;">Teléfonos Padres / Tutor</th>
                <th style="text-align: left;">Dirección y Población</th>
                <th style="width: 45px; text-align: center;">Ingreso</th>
                <th style="width: 75px; text-align: center;">Rol</th>
                <th style="width: 70px; text-align: center;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $u)
                @php
                    $age = $u->birth_date ? \Carbon\Carbon::parse($u->birth_date)->age : null;
                    
                    // Concatenar teléfonos familiares
                    $familyPhones = [];
                    if ($u->father_phone) $familyPhones[] = 'P: ' . $u->father_phone;
                    if ($u->mother_phone) $familyPhones[] = 'M: ' . $u->mother_phone;
                    if ($u->guardian_phone) $familyPhones[] = 'T: ' . $u->guardian_phone;
                    $familyPhonesStr = implode(' | ', $familyPhones);

                    // Dirección compacta
                    $dirParts = array_filter([$u->address, $u->postal_code, $u->city, $u->province]);
                    $fullAddress = implode(', ', $dirParts);

                    // Roles en español
                    $rolesEsp = [
                        'admin' => 'Administrador',
                        'treasurer' => 'Tesorero',
                        'director' => 'Director',
                        'musician' => 'Músico',
                        'external' => 'Externo',
                    ];
                    $rolNombre = $rolesEsp[$u->role] ?? ucfirst($u->role);
                @endphp
                <tr>
                    <td>
                        <strong class="text-gray-900">{{ $u->last_name }}, {{ $u->name }}</strong>
                    </td>
                    <td style="text-align: center; font-family: monospace;">{{ $u->nif ?: '-' }}</td>
                    <td style="text-align: center;">{{ $u->birth_date ? $u->birth_date->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: center;">
                        @if($age !== null)
                            <span class="{{ $age < 18 ? 'font-bold text-amber-700' : '' }}">{{ $age }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td style="word-break: break-all; color: #374151;">{{ $u->email }}</td>
                    <td style="font-weight: 500;">{{ $u->phone ?: '-' }}</td>
                    <td style="color: #4B5563;">
                        {{ $familyPhonesStr ?: '-' }}
                    </td>
                    <td style="color: #4B5563;">
                        {{ $fullAddress ?: '-' }}
                    </td>
                    <td style="text-align: center;">{{ $u->joining_year ?: '-' }}</td>
                    <td style="text-align: center;">{{ $rolNombre }}</td>
                    <td style="text-align: center;">
                        @if($u->is_active)
                            <span style="color: #047857; font-weight: 600;">Activo</span>
                        @elseif($u->privacy_accepted_at)
                            <span style="color: #B45309; font-weight: 600;">Pendiente</span>
                        @else
                            <span style="color: #B91C1C; font-weight: 600;">Baja</span>
                        @endif
                        @if(!$u->is_active && $u->leave_reason)
                            <div style="font-size: 7.5px; color: #6B7280; margin-top: 1px;">({{ Str::limit($u->leave_reason, 20) }})</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 15px; color: #6B7280;">
                        No se encontraron miembros para el criterio seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 pt-2 border-t border-gray-200 text-[8.5px] text-gray-500 flex justify-between items-center">
        <span>Documento administrativo de uso interno confidencial - {{ $bandName }}</span>
        <span>Página 1</span>
    </div>
</body>
</html>
