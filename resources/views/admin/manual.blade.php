<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Manual del Administrador</h2>
                <p class="mt-2 text-sm text-gray-400">Guía completa para la gestión de músicos, partituras, eventos y configuración general de la banda.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button onclick="window.print()" class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 print:hidden">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                    </svg>
                    Imprimir / Guardar PDF
                </button>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Dashboard -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Dashboard</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Es la pantalla principal para la junta directiva. Ofrece gráficas y estadísticas clave en tiempo real:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Músicos por instrumento:</strong> Para identificar qué cuerdas están más o menos reforzadas.</li>
                        <li><strong>Porcentaje de Asistencia:</strong> Gráficas comparativas del mes actual respecto a los meses anteriores.</li>
                        <li><strong>Ranking de Faltas:</strong> Los componentes con más ausencias recientes.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Gestión de Músicos -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Músicos / Componentes</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>En este apartado puedes registrar nuevas altas, modificar datos y gestionar a los usuarios:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Asignación de Roles:</strong> Al crear o editar puedes elegir entre Administrador, Músico, Tesorero o Externo.</li>
                        <li><strong>Asignación de Instrumentos:</strong> Permite asignarles instrumentos del catálogo y especificar un <em>número de serie</em> (útil si el instrumento pertenece a la banda).</li>
                        <li><strong>Gestión de Bajas:</strong> En lugar de borrar, puedes "Desactivar" un usuario, indicando el motivo de su baja temporal o definitiva.</li>
                        <li><strong>Control de Faltas:</strong> Desde su ficha de edición, puedes ver rápidamente su propio historial de inasistencias.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Archivo Musical -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19.5V15m0 0v-4.5m0 4.5h.008M15 19.5V15m0 0v-4.5m0 4.5h.008M12 21a9.003 9.003 0 008.354-5.646.998.998 0 00.146-.5V5.25A2.25 2.25 0 0018.25 3H5.75A2.25 2.25 0 003.5 5.25v9.604c0 .17.05.334.146.5A9.003 9.003 0 0012 21z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Archivo Musical</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Tu biblioteca digital de partituras (obras, marchas, pasodobles...).</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Instrumentos que intervienen:</strong> Debes indicar qué instrumentos participan en la obra para que sus respectivos músicos puedan descargarla.</li>
                        <li><strong>Ficheros:</strong> Puedes subir un PDF con la partitura general o guiones, y un audio en MP3 para que los músicos puedan estudiar.</li>
                        <li><strong>Buscador:</strong> Un filtro rápido te permite encontrar cualquier obra por su nombre.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Eventos y Asistencia -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-purple-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Eventos y Planning</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>En este módulo planificas todas las actividades. Todo lo que crees aquí aparecerá en el "Planning" de los músicos automáticamente.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Tipo y Color:</strong> A cada evento (Ensayo, Concierto, etc.) se le puede asignar un color. Estos colores se usarán para exportar el planning en PDF.</li>
                        <li><strong>Puntaje:</strong> Permite definir cuánto "pesa" el evento, para posteriores rankings.</li>
                        <li><strong>Pasar Lista (Asistencia):</strong> Selecciona un evento pasado y pulsa el botón de "Asistencia". Por defecto todos los músicos marcan como "Presente". Solo necesitas cambiar los que han faltado o están justificados.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Avisos y Noticias -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-pink-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Avisos y Noticias</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>El tablón de anuncios virtual para comunicarte con los músicos.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Visibilidad Programada:</strong> Puedes indicar una fecha de "Activo desde" y "Activo hasta" para que la noticia desaparezca automáticamente del panel de los músicos cuando ya haya caducado.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Configuración General -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-gray-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Ajustes / Configuración</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Aquí se administran los parámetros globales de la aplicación.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Nombre de la Banda:</strong> Cambia el título global que aparece en la web y los reportes PDF.</li>
                        <li><strong>Estatutos e Historia:</strong> Editores de texto completo (WYSIWYG) para redactar y dar formato a los estatutos y la biografía de la asociación.</li>
                        <li><strong>Tiempo de Sesión:</strong> Ajusta en minutos cuánto tiempo puede estar alguien sin usar la aplicación antes de que se cierre automáticamente su sesión (recomendable: 120 minutos).</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
