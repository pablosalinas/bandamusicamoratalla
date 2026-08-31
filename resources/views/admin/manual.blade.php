<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Manual del Administrador</h2>
                <p class="mt-2 text-sm text-gray-400">Guía rápida para el uso y gestión de la plataforma directiva de la banda.</p>
            </div>
        </div>
    </x-slot>

    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">

        <!-- Contabilidad y Presupuestos -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg md:col-span-2 border-l-4 border-emerald-500">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-emerald-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Contabilidad y Presupuestos</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Nuevo módulo completo para la gestión financiera de la banda, accesible para Tesoreros y Administradores.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Ejercicios Económicos:</strong> Crea años fiscales con fechas de inicio y fin. El listado se ordena automáticamente de forma descendente por fechas para mostrarte los más recientes primero.</li>
                        <li><strong>Movimientos:</strong> Dentro de cada ejercicio puedes añadir Ingresos y Gastos. Puedes subir facturas o recibos adjuntos (PDF/Imágenes) a cada movimiento.</li>
                        <li><strong>Punteo (Conciliación):</strong> Marca los movimientos como "Punteados" cuando estén comprobados en el banco. Un movimiento punteado queda bloqueado y no puede ser editado ni eliminado a menos que le quites el punteo primero. Toda acción de quitar un punteo se registra en el log por seguridad.</li>
                        <li><strong>Gráficos Comparativos:</strong> Entrando a un ejercicio, verás un panel analítico. Puedes seleccionar cuántos años hacia atrás deseas comparar y el sistema generará dinámicamente gráficos de pastel (anillos concéntricos por año) y de barras agrupadas comparando Ingresos, Gastos y Saldos.</li>
                        <li><strong>Informes PDF:</strong> Puedes generar un balance final en formato PDF listo para imprimir o presentar en asamblea, que incluirá el logotipo principal de la banda.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Músicos, Usuarios e IBAN -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Gestión de Usuarios</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Control total sobre los músicos y directivos dados de alta en la plataforma.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Permisos:</strong> Puedes asignar roles de Músico, Director, Tesorero o Administrador, cada uno con acceso a sus respectivas secciones.</li>
                        <li><strong>Ficha Completa:</strong> Podrás ver su contacto, talla de ropa, historial de asistencia e instrumentos prestados.</li>
                        <li><strong>Seguridad IBAN:</strong> Para proteger la privacidad financiera, la cuenta bancaria de los músicos solo es visible para el Tesorero y el Administrador. Al introducir una cuenta, el sistema valida automáticamente los dígitos de control matemáticos y rechaza los IBAN que no sean reales.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Inventario -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-orange-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Inventario de Instrumentos</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Catálogo vs Inventario:</strong> Tienes dos pantallas, el <em>Catálogo</em> para gestionar los tipos genéricos de instrumentos, y el <em>Inventario de la Banda</em> que lista todos los instrumentos físicos (incluyendo marca, modelo y número de serie).</li>
                        <li><strong>Gestión de Bajas (Desactivar):</strong> ⚠️ Al intentar eliminar un registro te avisará: es preferible "Desactivarlo" y añadir un motivo de baja, para conservar su historial y evitar borrados accidentales.</li>
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
                    <h3 class="text-xl font-bold text-white">Eventos, Asistencia y Juntas</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Planificación de actividades y registro oficial de la directiva.</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Control de Asistencia:</strong> Crea ensayos o conciertos en el Planning. Una vez pasado el evento, podrás entrar a "Asistencia" y llevar un control riguroso de qué músicos han asistido, faltado o justificado su ausencia. Esto alimentará sus paneles individuales y las estadísticas.</li>
                        <li><strong>Actas de Juntas:</strong> Editor avanzado para redactar las actas con tipografías, negritas y justificados. Cuando las exportes, el sistema generará automáticamente un PDF perfecto.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Avisos, Noticias, Carrusel y Logos -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-pink-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Configuración y Aspecto Visual</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Logos de la Banda:</strong> Desde <em>Configuración</em> puedes subir múltiples logotipos de la asociación. El sistema rotará entre ellos automáticamente en la web. Además, puedes asignarles un "orden de prioridad": el logotipo principal se usará para los reportes PDF oficiales (como el de contabilidad).</li>
                        <li><strong>Avisos / Noticias:</strong> Aparecerán en la web principal y en el panel de los músicos. Puedes establecer fechas de caducidad para que desaparezcan solas.</li>
                        <li><strong>Carrusel Principal:</strong> Sube fotos y vídeos a la portada de inicio con su orden y texto superpuesto.</li>
                        <li><strong>Marca de Agua:</strong> Al subir cualquier foto a la web, se añade "www.bandamusicamoratalla.com" en la esquina.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Estadísticas y Registros -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg md:col-span-2">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Analítica y Seguridad</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Estadísticas Web:</strong> Un panel gráfico que te muestra en tiempo real cuánta gente visita la web, qué secciones ven más, desde qué países acceden y si usan móviles o PCs.</li>
                        <li><strong>Registros de Accesos:</strong> Puedes visualizar y buscar los logins y logouts de tus usuarios, así como filtrar las visitas por IP, fechas o ubicaciones.</li>
                        <li><strong>Seguridad de Datos Bancarios:</strong> Como medida de protección, los números de cuenta bancaria solo están visibles para administradores principales y usuarios con rol de "Tesorero".</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
