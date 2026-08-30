<x-admin-layout>
    <x-slot name="header">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white">Manual del Músico</h2>
                <p class="mt-2 text-sm text-gray-400">Guía de uso para las funciones del panel de músicos.</p>
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

    <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Mi Panel -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Mi Panel (Inicio)</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Es la pantalla principal que ves al entrar. En ella encontrarás un resumen de toda tu actividad en la banda.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Datos Personales:</strong> Un resumen de tus datos de contacto (nota: tu cuenta bancaria o IBAN está oculta por motivos de privacidad).</li>
                        <li><strong>Tus Instrumentos:</strong> Lista detallada de los instrumentos que tienes asignados, indicando marca, modelo, número de serie y mostrando <strong>fotos reales</strong> de tu instrumento.</li>
                        <li><strong>Tu Repertorio:</strong> Muestra las partituras asociadas a tus instrumentos. Puedes usar el botón de <span class="text-amber-500">Descargar PDF</span> o escuchar el <span class="text-amber-500">Audio (MP3)</span> para ensayar.</li>
                        <li><strong>Historial de Faltas:</strong> Un resumen de los ensayos o eventos a los que has faltado o justificado ausencia.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Planning -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Planning</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>En el menú lateral encontrarás la opción <strong>Planning</strong>. Muestra todos los eventos y actividades futuras programadas por la directiva.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Están separados por colores según el <strong>tipo de actividad</strong> (Ensayos, procesiones, conciertos...).</li>
                        <li>Puedes descargar un <strong>PDF resumen mensual</strong> pulsando en el botón superior, ideal para imprimir o pasar por WhatsApp.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Perfil y Datos Personales -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Mi Perfil y Contraseña</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>Para acceder a tu perfil, pulsa en tu miniatura en la esquina superior derecha y haz clic en <strong>Perfil</strong>.</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Foto de perfil:</strong> Puedes subir o actualizar tu foto para que la directiva te identifique fácilmente (se verá tu miniatura en los listados).</li>
                        <li><strong>Datos personales:</strong> Podrás ver tus datos de contacto (si necesitas modificarlos, debes avisar al administrador).</li>
                        <li><strong>Cambio de contraseña:</strong> Por tu seguridad, puedes cambiar tu contraseña en cualquier momento usando esta pantalla.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Estatutos y Avisos -->
        <div class="bg-gray-900 overflow-hidden shadow ring-1 ring-white/10 sm:rounded-lg">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-purple-500/10 rounded-lg">
                        <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Estatutos y Avisos</h3>
                </div>
                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>En el menú inferior izquierdo de tu pantalla verás la opción de los <strong>Estatutos</strong> de la banda, que siempre están disponibles para consulta o impresión.</p>
                    <p>Además, cada vez que la directiva publique un nuevo <strong>Aviso o Noticia</strong>, lo verás destacado en la parte superior de tu panel mientras siga vigente.</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
