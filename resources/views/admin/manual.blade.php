<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    Portal de Gestión Directiva
                </span>
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white">Manual Integral del Administrador</h2>
                <p class="mt-2 text-sm text-gray-400 max-w-2xl">
                    Guía de referencia completa para la Junta Directiva y Administradores: gestión de altas, validación de instrumentos, catálogo y archivo musical, eventos, control de presencia y tesorería.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3 shrink-0">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500 transition-all cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.728 9.39A3 3 0 004 12v6m16-6a3 3 0 00-2.728-2.61M6.728 9.39A3 3 0 0110 7h4a3 3 0 013.272 2.39M6.728 9.39L6 6h12l-.728 3.39M10 16h4M8 21h8" />
                    </svg>
                    Imprimir / Guardar en PDF
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Índice Rápido Directivo -->
    <div class="mt-6 p-4 rounded-xl bg-gray-900/60 border border-gray-800 backdrop-blur-sm">
        <h3 class="text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Secciones del Manual Directivo:</h3>
        <div class="flex flex-wrap gap-2 text-xs">
            <a href="#ajustes-generales" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">1. Configuración y Conmutadores</a>
            <a href="#usuarios-musicos" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">2. Gestión de Usuarios y Roles</a>
            <a href="#inventario-instrumentos" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">3. Inventario y Validación de Músicos</a>
            <a href="#archivo-musical" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">4. Archivo de Obras y Partituras</a>
            <a href="#eventos-asistencia" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">5. Eventos, Asistencia y Juntas</a>
            <a href="#contabilidad-tesoreria" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">6. Contabilidad y Conciliación</a>
            <a href="#seguridad-analitica" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-blue-600/30 transition-colors">7. Seguridad y Auditoría</a>
        </div>
    </div>

    <div class="mt-8 space-y-8">

        <!-- SECCIÓN 1: CONFIGURACIÓN Y CONMUTADORES -->
        <div id="ajustes-generales" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-pink-500/10 text-pink-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.15.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">1. Configuración del Sistema y Conmutadores Maestros</h3>
                    <p class="text-xs text-gray-400">Controla la apertura pública de altas, subida de instrumentos y personalización visual.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    Desde el menú <strong>Configuración &rarr; Ajustes Generales</strong>, la Junta Directiva puede gobernar el comportamiento de toda la plataforma sin necesidad de recurrir a soporte técnico:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-3">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-pink-500/20">
                        <div class="flex items-center gap-2 text-pink-400 font-semibold mb-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-pink-400"></span>
                            Permitir Registro de Músicos
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Si está <strong class="text-emerald-400">Activado</strong>, la pantalla de login mostrará el enlace "¿Aún no tienes cuenta? Solicitar alta aquí" para que los músicos completen su ficha. Si se <strong class="text-red-400">Desactiva</strong>, el formulario se cierra y nadie ajeno podrá intentar registrarse.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-pink-500/20">
                        <div class="flex items-center gap-2 text-pink-400 font-semibold mb-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-pink-400"></span>
                            Permitir a Músicos Registrar sus Instrumentos
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Permite que los músicos añadan sus instrumentos particulares con fotos y facturas desde su propio panel. Si se desactiva, los músicos solo podrán ver sus instrumentos pero no añadir nuevos (útil si la banda desea centralizar todo el inventario).
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <div class="flex items-center gap-2 text-amber-400 font-semibold mb-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            Email Oficial de la Banda y RGPD
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Dirección de correo que aparece reflejada en los descargos de responsabilidad legal y el ejercicio de derechos ARCO de los músicos.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <div class="flex items-center gap-2 text-blue-400 font-semibold mb-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                            Gestión de Logotipos de la Asociación
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Puedes subir múltiples logotipos con pesos/prioridades. El logotipo principal se incrusta automáticamente en los informes oficiales PDF (como balances contables y autorizaciones parentales).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: GESTIÓN DE USUARIOS Y ROLES -->
        <div id="usuarios-musicos" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-blue-500/10 text-blue-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">2. Gestión de Usuarios, Fichas de Músicos y Roles</h3>
                    <p class="text-xs text-gray-400">Aprobación de nuevas solicitudes, asignación de privilegios y homogenización de datos.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    En el módulo <strong>Músicos / Usuarios</strong> dispones del censo completo de componentes, directores y personal directivo.
                </p>

                <div class="space-y-3">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-white mb-1 flex items-center gap-2">
                            <span class="px-2 py-0.5 text-xs bg-blue-500/20 text-blue-400 rounded">Paso a paso</span>
                            Validación de Nuevas Solicitudes de Alta
                        </h4>
                        <ol class="list-decimal pl-5 space-y-1.5 text-xs text-gray-300">
                            <li>Cuando un músico rellena el formulario de alta pública, su usuario se crea en estado <strong>Pendiente</strong> o inactivo según la configuración.</li>
                            <li>El administrador puede buscarlo en la tabla con el buscador en tiempo real por nombre, DNI o instrumento.</li>
                            <li>Al abrir su ficha, puedes revisar sus datos, verificar su DNI y teléfono, y marcarlo como <strong>Activo</strong> para que pueda iniciar sesión.</li>
                            <li><strong>Menores de edad:</strong> Si la fecha de nacimiento corresponde a un menor de 18 años, el sistema exige y muestra los teléfonos de contacto de los padres/tutores.</li>
                        </ol>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-white mb-1">Jerarquía de Roles de Acceso</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 text-xs mt-2">
                            <div class="p-2.5 rounded bg-gray-900 border border-gray-800">
                                <strong class="text-amber-400 block mb-1">Músico</strong>
                                Acceso restringido al portal de músico: sus partituras asignadas, atril digital, sus instrumentos particulares y justificaciones.
                            </div>
                            <div class="p-2.5 rounded bg-gray-900 border border-gray-800">
                                <strong class="text-purple-400 block mb-1">Director</strong>
                                Acceso al archivo musical completo, planning de ensayos/conciertos y pase de lista/asistencias.
                            </div>
                            <div class="p-2.5 rounded bg-gray-900 border border-gray-800">
                                <strong class="text-emerald-400 block mb-1">Tesorero</strong>
                                Control total del módulo de Contabilidad, punteo de movimientos bancarios y visualización de cuentas IBAN.
                            </div>
                            <div class="p-2.5 rounded bg-gray-900 border border-gray-800">
                                <strong class="text-red-400 block mb-1">Administrador</strong>
                                Acceso global a todos los módulos, altas, inventario, configuración y registros de seguridad.
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-emerald-500/20">
                        <h4 class="font-bold text-emerald-400 mb-1">Homogeneidad de Datos (Mayúsculas Automáticas)</h4>
                        <p class="text-xs text-gray-300">
                            Para mantener la máxima pulcritud en listados, informes de bandas federadas y listados de asistencia, todos los nombres, apellidos y direcciones se transforman automáticamente a <strong>MAYÚSCULAS</strong> tanto en el formulario web como en el guardado en base de datos.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: INVENTARIO Y VALIDACIÓN DE MÚSICOS -->
        <div id="inventario-instrumentos" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-orange-500/10 text-orange-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">3. Inventario, Catálogo y Validación de Instrumentos</h3>
                    <p class="text-xs text-gray-400">Gestión de instrumentos propios de la banda y aprobación de los registrados por los músicos.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    El inventario se divide en dos conceptos esenciales:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-orange-400 mb-1">A. Catálogo de Instrumentos</h4>
                        <p class="text-xs text-gray-300 mb-2">
                            Define las familias y tipos generales: <em>Clarinete Si♭, Saxofón Alto, Trompeta Si♭, Tuba, Percusión, etc.</em>
                        </p>
                        <p class="text-xs text-gray-400">
                            Sirve como maestro para asociar los instrumentos físicos del inventario y para filtrar las partituras del archivo musical.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-orange-400 mb-1">B. Inventario Físico (Banda y Particulares)</h4>
                        <p class="text-xs text-gray-300 mb-2">
                            Cada unidad física concreta con su <strong>Marca, Modelo, Número de Serie y Estado de conservación</strong>.
                        </p>
                        <p class="text-xs text-gray-400">
                            Permite asignar instrumentos en préstamo a músicos y controlar devoluciones o reparaciones.
                        </p>
                    </div>
                </div>

                <!-- Validación de instrumentos de músicos -->
                <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30">
                    <div class="flex items-center gap-2 text-amber-400 font-bold mb-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Validación de Instrumentos Registrados por Músicos
                    </div>
                    <p class="text-xs text-gray-300 leading-relaxed">
                        Cuando un músico da de alta un instrumento propio desde su portal (con su captcha y factura de compra opcional), el instrumento aparece en el inventario con el distintivo <span class="px-2 py-0.5 rounded bg-yellow-500/20 text-yellow-300 font-semibold">Pendiente de Verificación</span>.
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-xs text-gray-300">
                        <li>El administrador puede pulsar directamente sobre el botón <strong>"Verificar"</strong> en la tabla de inventario para confirmarlo con 1 solo clic.</li>
                        <li>Puede abrir la ficha del instrumento para inspeccionar la factura adjunta y cotejar el número de serie.</li>
                        <li><strong>Evitar borrados accidentales:</strong> Si un instrumento se retira o se avería definitivamente, el sistema recomienda <em>Desactivarlo</em> en lugar de borrarlo, preservando el histórico patrimonial.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: ARCHIVO MUSICAL -->
        <div id="archivo-musical" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5V15m0 0v-4.5m0 4.5h.008M15 19.5V15m0 0v-4.5m0 4.5h.008M12 21a9.003 9.003 0 008.354-5.646.998.998 0 00.146-.5V5.25A2.25 2.25 0 0018.25 3H5.75A2.25 2.25 0 003.5 5.25v9.604c0 .17.05.334.146.5A9.003 9.003 0 0012 21z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">4. Archivo de Obras, Reparto de Papeles y Atril Digital</h3>
                    <p class="text-xs text-gray-400">Cómo catalogar obras, asignar partituras por instrumento/voz y habilitar el visor digital.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    El <strong>Archivo Musical</strong> permite mantener digitalizado todo el patrimonio musical de la banda (pasodobles, marchas moras, cristianas, procesionales, obras sinfónicas...).
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-white text-xs uppercase text-emerald-400 mb-1">Ficha de la Obra</h4>
                        <p class="text-xs text-gray-400">
                            Título, compositor, arreglista, género musical, dificultad y ubicación en el archivo físico (número de caja o archivador).
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-white text-xs uppercase text-emerald-400 mb-1">Reparto por Papeles</h4>
                        <p class="text-xs text-gray-400">
                            Asocia cada PDF al instrumento del catálogo y especifica la voz o papel (<em>TODOS, 1º, 2º, 3º, 4º</em>) para que el atril digital de cada músico le filtre directamente la suya.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-white text-xs uppercase text-emerald-400 mb-1">Audio de Referencia</h4>
                        <p class="text-xs text-gray-400">
                            Posibilidad de subir ficheros MP3 para que los músicos puedan ensayar con la interpretación o grabación de referencia desde su teléfono u ordenador.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: EVENTOS, ASISTENCIA Y JUNTAS -->
        <div id="eventos-asistencia" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-purple-500/10 text-purple-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">5. Planning, Control de Asistencia y Actas de Junta</h3>
                    <p class="text-xs text-gray-400">Organización del calendario de ensayos/actuaciones y custodia de actas oficiales.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-purple-400 mb-1">Planning y Asistencia</h4>
                        <ul class="list-disc pl-5 space-y-1 text-xs text-gray-300">
                            <li>Crea ensayos, pasacalles, procesiones y conciertos indicando fecha, lugar, uniforme requerido y obras a interpretar.</li>
                            <li><strong>Pase de lista:</strong> Permite marcar <em>Asiste, Falta o Justificado</em>.</li>
                            <li>Alimenta el porcentaje de asistencia personal de cada músico y el cómputo global para la junta directiva.</li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-purple-400 mb-1">Actas de Juntas Directivas</h4>
                        <ul class="list-disc pl-5 space-y-1 text-xs text-gray-300">
                            <li>Redacción directa en el editor con soporte para negritas, listas y formateo.</li>
                            <li>Generación automática de PDF con membrete institucional listo para la firma del Secretario y Presidente.</li>
                            <li>Búsqueda rápida por fecha y orden del día.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 6: CONTABILIDAD Y CONCILIACIÓN -->
        <div id="contabilidad-tesoreria" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden border-l-4 border-l-emerald-500">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">6. Contabilidad, Tesorería y Conciliación Bancaria</h3>
                    <p class="text-xs text-gray-400">Control de ingresos, gastos, adjuntos de factura, punteo blindado e informes analíticos.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    El módulo económico está restringido exclusivamente a usuarios con rol de <strong>Tesorero</strong> y <strong>Administrador</strong>.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <strong class="text-white block mb-1 text-sm font-semibold">Ejercicios Económicos</strong>
                        Crea los años fiscales (ej: 2024, 2025, 2026). Los ejercicios se listan ordenados cronológicamente de forma descendente para tener el año activo siempre a mano.
                    </div>
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <strong class="text-white block mb-1 text-sm font-semibold">Movimientos y Facturas</strong>
                        Registra cada cobro o pago indicando concepto, beneficiario/proveedor, partida e importe. Permite adjuntar el PDF o foto de la factura oficial.
                    </div>
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-emerald-500/30 bg-emerald-950/10">
                        <strong class="text-emerald-400 block mb-1 text-sm font-semibold">Punteo (Conciliación)</strong>
                        Al pulsar "Puntear", el movimiento queda <strong>bloqueado contra borrados o ediciones accidentales</strong>. Solo un administrador o tesorero puede despuntearlo, quedando rastro de la acción.
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800 flex flex-col sm:flex-row gap-4 items-center">
                    <div class="text-xs text-gray-300 space-y-1 flex-1">
                        <h4 class="font-bold text-white text-sm">Informes Oficiales y Gráficos Multianuales</h4>
                        <p>
                            El sistema compara dinámicamente el ejercicio actual con hasta 5 años anteriores, generando gráficos circulares de ingresos/gastos y comparativas de saldo neto. Puedes exportar el balance con 1 clic a un <strong>PDF oficial con el logotipo institucional</strong> para asambleas de socios.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 7: SEGURIDAD Y AUDITORÍA -->
        <div id="seguridad-analitica" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-amber-500/10 text-amber-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">7. Analítica Web, Auditoría de Accesos y Buenas Prácticas</h3>
                    <p class="text-xs text-gray-400">Protección de datos personales, registro de inicios de sesión y estadísticas de visitas.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <strong class="text-amber-400 block mb-1">Protección de Cuentas IBAN</strong>
                        Los números de cuenta para pagos de actuaciones o dietas están fuertemente custodiados. Solo tesorería y administradores pueden visualizarlos. Además, el validador rechaza cualquier IBAN con dígitos erróneos.
                    </div>
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <strong class="text-amber-400 block mb-1">Registro de Logins / IPs</strong>
                        Permite auditar quién ha iniciado sesión, desde qué dirección IP y a qué hora, garantizando la trazabilidad ante cualquier anomalía o acceso indebido.
                    </div>
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <strong class="text-amber-400 block mb-1">Métricas de Tráfico Web</strong>
                        Panel estadístico de visitantes que muestra visualizaciones de páginas, dispositivos utilizados (móvil vs ordenador) y distribución geográfica de visitantes.
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>

