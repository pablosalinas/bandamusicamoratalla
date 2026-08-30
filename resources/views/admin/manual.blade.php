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
       <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        
        <!-- Músicos / Usuarios -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Músicos e Inventario</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Gestiona los perfiles y el material entregado a los miembros de la banda.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Foto de Perfil:</strong> El listado muestra la miniatura de la foto del músico. Si no tiene, se muestran sus iniciales automáticamente.</li>
                        <li><strong>Instrumentos:</strong> Desde la edición del músico puedes asignarle instrumentos. Además del tipo, puedes indicar <strong>Marca y Modelo</strong>, número de serie, y subir **fotos** de ese instrumento concreto, que el músico podrá ver en su panel.</li>
                        <li><strong>Catálogo vs Inventario:</strong> Tienes dos pantallas, el <em>Catálogo</em> para gestionar los tipos genéricos de instrumentos, y el <em>Inventario de la Banda</em> que lista todos los instrumentos que actualmente están prestados a los músicos (incluyendo marca, modelo y número de serie).</li>
                        <li><strong>Gestión de Bajas (Desactivar):</strong> ⚠️ Al intentar eliminar cualquier registro te avisará: es preferible "Desactivarlo" y añadir un motivo de baja, para conservar su historial de faltas y evitar borrados accidentales.</li>
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
                        <li><strong>Instrumentos que intervienen:</strong> Debes indicar qué instrumentos participan en la obra para que sus respectivos músicos puedan descargarla en sus paneles.</li>
                        <li><strong>Ficheros:</strong> Puedes subir un PDF con la partitura general o guiones, y un audio en MP3 para que los músicos puedan estudiar.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Eventos, Asistencia y Juntas -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-purple-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Eventos y Juntas Directivas</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Planificación de actividades y registro oficial de la directiva.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Eventos y Planning:</strong> Crea ensayos o conciertos asignándoles un color. Al seleccionar un evento pasado, usa "Asistencia" para marcar las faltas, repercutiendo directamente en el historial del músico.</li>
                        <li><strong>Actas de Juntas:</strong> Se incorpora un editor avanzado (CKEditor 5) para redactar las actas con tipografías, negritas y justificados. Cuando las guardes y exportes, el sistema generará automáticamente un PDF perfecto con el texto enriquecido.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Avisos, Noticias y Carrusel -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-pink-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Portada Web (Avisos y Carrusel)</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Modifica el contenido público y la comunicación directa.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Avisos / Noticias:</strong> Lo que publiques aquí aparecerá en la web principal y en el panel de los músicos. Puedes establecer fechas de caducidad para que desaparezcan solas.</li>
                        <li><strong>Carrusel Principal:</strong> Desde <em>Configuración</em> puedes subir fotos y vídeos al carrusel de la página de inicio. Te permite definir el orden, la descripción que aparece por encima y la velocidad de paso.</li>
                        <li><strong>Marca de Agua:</strong> Al subir cualquier foto a la web (instrumentos, perfil, carrusel) el sistema añade automáticamente la marca "www.bandamusicamoratalla.com" en la esquina inferior derecha.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Estadísticas y Registros -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Analítica y Privacidad</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Estadísticas Web:</strong> Un panel gráfico que te muestra en tiempo real cuánta gente visita la web, qué secciones ven más, desde qué países acceden y si usan móviles o PCs.</li>
                        <li><strong>Registros de Accesos:</strong> Puedes visualizar y buscar los logins y logouts de tus usuarios, así como filtrar las visitas por IP, fechas o ubicaciones. Todo se auto-depura para mostrarte los últimos 7 días por defecto.</li>
                        <li><strong>Privacidad de IBAN:</strong> Como medida de seguridad estricta, los números de cuenta bancaria solo están visibles para administradores principales y usuarios con rol de "Tesorero".</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>

