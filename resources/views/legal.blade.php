<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aviso Legal y Política de Privacidad - Banda de Música de Moratalla</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-950 text-gray-200">

    <!-- Simple Navigation -->
    <nav class="bg-gray-950/90 backdrop-blur-md shadow-lg border-b border-gray-800 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-gray-950 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-white hover:text-amber-500 transition-colors">← Volver al inicio</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-6 py-16">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 mb-8">Aviso Legal y Política de Privacidad</h1>
        
        <div class="prose prose-invert prose-amber max-w-none">
            <h2>1. Información General</h2>
            <p>
                En cumplimiento de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), se informa que este sitio web es titularidad de la <strong>Asociación Banda de Música de Moratalla</strong>.
            </p>
            <p>
                Para cualquier consulta, queja o sugerencia, puede ponerse en contacto con nosotros a través de nuestro correo electrónico oficial: <strong><a href="mailto:bandamusicademoratalla@gmail.com" class="text-amber-500 hover:text-amber-400">bandamusicademoratalla@gmail.com</a></strong>.
            </p>

            <h2>2. Política de Privacidad y Protección de Datos</h2>
            <p>
                De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo (Reglamento General de Protección de Datos - RGPD) y en la Ley Orgánica 3/2018 de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD), le informamos sobre el tratamiento de sus datos en este sitio web.
            </p>
            
            <h3>Recopilación y finalidad de los datos</h3>
            <p>
                Este sitio web <strong>no recaba ni manipula datos personales sensibles</strong> de los usuarios visitantes. La navegación por la página pública es totalmente anónima. El acceso al panel privado está restringido exclusivamente a los músicos y miembros de la asociación mediante credenciales autorizadas previamente por la dirección.
            </p>
            
            <h3>Derechos de los usuarios</h3>
            <p>
                Cualquier persona tiene derecho a obtener confirmación sobre si estamos tratando datos personales que les conciernan. Las personas interesadas tienen derecho a acceder a sus datos personales, así como a solicitar la rectificación de los datos inexactos o, en su caso, solicitar su supresión. Puede ejercer estos derechos enviando un correo electrónico a <strong>bandamusicademoratalla@gmail.com</strong>.
            </p>

            <h2>3. Propiedad Intelectual</h2>
            <p>
                El código fuente, los diseños gráficos, las imágenes, las fotografías, los sonidos, las animaciones, el software, los textos, así como la información y los contenidos que se recogen en este sitio web están protegidos por la legislación española sobre los derechos de propiedad intelectual e industrial a favor de la Asociación Banda de Música de Moratalla. No se permite la reproducción total o parcial de esta web sin el permiso previo y por escrito de la asociación.
            </p>

            <h2>4. Exclusión de Responsabilidad</h2>
            <p>
                La asociación no se hace responsable del contenido de los enlaces a otras páginas web que no sean titularidad suya y que, por tanto, no pueden ser controladas por esta.
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 bg-gray-950 py-8 text-center text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} Asociación Banda de Música de Moratalla. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
