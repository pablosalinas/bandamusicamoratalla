<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 mb-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    Portal Oficial de Músicos
                </span>
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white">Guía y Manual del Músico</h2>
                <p class="mt-2 text-sm text-gray-400 max-w-2xl">
                    Descubre todo lo que puedes hacer en tu Área de Músico: desde registrar tus instrumentos y consultar tu repertorio en el atril digital hasta gestionar justificantes parentales y el control de asistencia.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3 shrink-0">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-lg shadow-amber-600/20 hover:bg-amber-500 transition-all cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.728 9.39A3 3 0 004 12v6m16-6a3 3 0 00-2.728-2.61M6.728 9.39A3 3 0 0110 7h4a3 3 0 013.272 2.39M6.728 9.39L6 6h12l-.728 3.39M10 16h4M8 21h8" />
                    </svg>
                    Imprimir / Guardar en PDF
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Índice Rápido -->
    <div class="mt-6 p-4 rounded-xl bg-gray-900/60 border border-gray-800 backdrop-blur-sm">
        <h3 class="text-xs font-semibold text-amber-400 uppercase tracking-wider mb-2">Contenido de esta guía:</h3>
        <div class="flex flex-wrap gap-2 text-xs">
            <a href="#proceso-alta" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-amber-600/30 transition-colors">1. Alta de Nuevo Músico</a>
            <a href="#mis-datos" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-white hover:bg-amber-600/30 transition-colors">2. Datos Personales y RGPD</a>
            <a href="#mis-instrumentos" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:bg-amber-600/30 transition-colors">3. Mis Instrumentos y Facturas</a>
            <a href="#partituras-atril" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:bg-amber-600/30 transition-colors">4. Partituras y Atril Digital</a>
            <a href="#planning-asistencia" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:bg-amber-600/30 transition-colors">5. Planning y Asistencia</a>
            <a href="#justificantes-menores" class="px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:bg-amber-600/30 transition-colors">6. Menores y Justificantes</a>
        </div>
    </div>

    <div class="mt-8 space-y-8">

        <!-- SECCIÓN 1: PROCESO DE ALTA -->
        <div id="proceso-alta" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">1. Proceso de Alta de Nuevo Músico</h3>
                    <p class="text-xs text-gray-400">Cómo incorporarse a la plataforma de forma online y segura.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    Cuando el alta pública de músicos esté habilitada por la directiva, cualquier componente o nuevo aspirante puede registrarse cómodamente desde la pantalla de <strong>Acceso Músicos &rarr; Solicitar Alta</strong>.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 font-bold text-xs flex items-center justify-center mb-2">A</span>
                        <h4 class="font-semibold text-white mb-1">Datos Obligatorios</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Nombre, apellidos, NIF/NIE verificado, correo electrónico, teléfono móvil, fecha de nacimiento y dirección completa (domicilio, C.P., localidad y provincia).
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 font-bold text-xs flex items-center justify-center mb-2">B</span>
                        <h4 class="font-semibold text-white mb-1">Menores de Edad</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Al introducir la fecha de nacimiento, el sistema calcula la edad. Si es menor de 18 años, <strong>es obligatorio facilitar al menos un teléfono de contacto</strong> (padre, madre o tutor legal).
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 font-bold text-xs flex items-center justify-center mb-2">C</span>
                        <h4 class="font-semibold text-white mb-1">Custodia de Contraseña</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Aparece un aviso destacado exigiendo marcar la casilla de confirmación de haber anotado y memorizado la contraseña en un lugar seguro antes de poder registrarse.
                        </p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-start gap-3 text-xs text-amber-200">
                    <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <strong>Validación previa:</strong> Una vez enviado el formulario, tu cuenta queda registrada con estado <em>"Pendiente de Aprobación"</em>. La directiva revisará los datos, confirmará tu incorporación y activará tu cuenta para que puedas acceder.
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: DATOS PERSONALES Y RGPD -->
        <div id="mis-datos" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">2. Tu Ficha Personal y Protección de Datos (RGPD)</h3>
                    <p class="text-xs text-gray-400">Transparencia sobre la información almacenada y tus derechos.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    En la cabecera de tu panel verás tu ficha resumen: Nombre y Apellidos (en formato mayúsculas homogéneo), DNI/NIF, Teléfono, Año de Incorporación, Dirección y Teléfonos de Tutores.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-semibold text-amber-400 text-xs uppercase tracking-wider mb-2">Aceptación de RGPD</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Tu ficha muestra la fecha exacta en la que aceptaste la política de privacidad. Los datos se tratan con la única finalidad de gestionar tu pertenencia a la banda, asignación de inventario y convocatorias de actos.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-semibold text-amber-400 text-xs uppercase tracking-wider mb-2">Ejercicio de Derechos</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Puedes ejercer en cualquier momento tus derechos de acceso, rectificación o supresión contactando directamente con la directiva a través del correo oficial configurado en la entidad.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: INSTRUMENTOS Y FACTURAS -->
        <div id="mis-instrumentos" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">3. Mis Instrumentos, Facturas y Número de Serie</h3>
                    <p class="text-xs text-gray-400">Control de inventario asignado por la banda y registro de instrumentos propios.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    La sección <strong>"Tus Instrumentos"</strong> agrupa tanto el material prestado por la asociación como tus instrumentos particulares:
                </p>

                <div class="space-y-3">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h4 class="font-bold text-white text-sm">Añadir mi Instrumento (Autogestión)</h4>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Cuando la opción esté activa en la banda, pulsa el botón <strong>"Añadir mi Instrumento"</strong> para registrar tu instrumento propio.
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 shrink-0">
                            Botón superior
                        </span>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800 space-y-2 text-xs text-gray-300">
                        <h4 class="font-bold text-amber-400 text-sm">Campos a rellenar en el alta:</h4>
                        <ul class="list-disc pl-5 space-y-1.5 text-gray-400">
                            <li><strong class="text-gray-200">Tipo de Instrumento:</strong> Clarinete, Trompeta, Flauta, Saxofón, etc. (Obligatorio).</li>
                            <li><strong class="text-gray-200">Marca y Modelo:</strong> Selecciona la marca (Yamaha, Buffet, Bach...) y escribe el modelo exacto (se guarda automáticamente en mayúsculas).</li>
                            <li><strong class="text-gray-200">Número de Serie:</strong> <span class="text-amber-300 font-semibold">Muy recomendado</span>. Facilita la identificación inequívoca de tu instrumento ante cualquier confusión, pérdida o sustracción.</li>
                            <li><strong class="text-gray-200">Tipo de Partitura habitual:</strong> Elige en el desplegable entre <em>TODOS, 1º, 2º, 3º, 4º</em> o déjalo en blanco.</li>
                            <li><strong class="text-gray-200">Año y Factura de Compra:</strong> Opcionalmente puedes indicar el año en que lo adquiriste y subir el archivo de la factura o ticket (PDF o imagen JPG/PNG) para tenerla siempre protegida y a mano.</li>
                            <li><strong class="text-gray-200">Captcha de Seguridad:</strong> Debes resolver una sencilla suma aritmética para confirmar el envío y evitar registros por error.</li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/30 text-xs text-yellow-200 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-yellow-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <strong>Estado "Pendiente de Validación":</strong> Al dar de alta un instrumento propio, aparecerá marcado con una etiqueta amarilla mientras la administración revisa los datos para incorporarlo al censo oficial de la banda.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: PARTITURAS Y ATRIL DIGITAL -->
        <div id="partituras-atril" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">4. Partituras y Visor de Atril Digital</h3>
                    <p class="text-xs text-gray-400">Estudia en casa o toca en directo desde tu tablet o móvil.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    Las partituras que ves en tu panel se filtran automáticamente en base al instrumento que tocas y tu voz o papel asignado (1º, 2º, etc.).
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-indigo-400 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /></svg>
                            Botón "Ver en Atril"
                        </h4>
                        <p class="text-gray-400 leading-relaxed">
                            Abre el visor optimizado para atril con fondo oscuro, pase de media página para no perder el compás y compatibilidad con pedal bluetooth de cambio de página.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800">
                        <h4 class="font-bold text-amber-400 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Botón "Descargar"
                        </h4>
                        <p class="text-gray-400 leading-relaxed">
                            Descarga el archivo PDF original de tu papel o guion para imprimirlo o anotarlo en tu aplicación favorita de partituras.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: PLANNING Y ASISTENCIA -->
        <div id="planning-asistencia" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-blue-500/10 text-blue-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">5. Planning y Control de Faltas de Asistencia</h3>
                    <p class="text-xs text-gray-400">Calendario oficial de ensayos, conciertos y procesiones.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Calendario Oficial:</strong> En el menú <em>Planning</em> puedes consultar las fechas, horas y lugares de cada evento, diferenciados por colores según el tipo de acto.</li>
                    <li><strong>Historial de Asistencia:</strong> En la parte inferior de tu panel tienes el desglose de faltas justificadas e injustificadas, con un selector de rango de fechas para que puedas consultar periodos específicos.</li>
                </ul>
            </div>
        </div>

        <!-- SECCIÓN 6: MENORES Y JUSTIFICANTES -->
        <div id="justificantes-menores" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 sm:p-8 shadow-xl">
            <div class="flex items-center gap-3.5 mb-5 border-b border-gray-800 pb-4">
                <div class="p-3 bg-red-500/10 text-red-400 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">6. Menores de Edad y Justificante Parental</h3>
                    <p class="text-xs text-gray-400">Descarga y firma de autorizaciones parentales oficiales.</p>
                </div>
            </div>

            <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                <p>
                    Si eres menor de 18 años, aparecerá un aviso permanente con el botón <strong>"Descargar Justificante"</strong>:
                </p>
                <div class="p-4 rounded-xl bg-gray-950/70 border border-gray-800 text-xs space-y-2">
                    <p class="text-gray-300">
                        Al pulsar en <em>Descargar Justificante</em>, se abre una ventana donde puedes elegir el evento concreto o dejarlo en blanco para rellenarlo a mano.
                    </p>
                    <p class="text-gray-400">
                        El sistema generará al instante un PDF con el escudo de la banda y tus datos pre-rellenados, listo para ser firmado por tu padre, madre o tutor legal.
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
