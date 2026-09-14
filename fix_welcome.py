import io
with io.open('resources/views/welcome.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

historia_start = content.find('    <!-- Historia Section -->')
noticias_start = content.find('    <!-- Noticias Section -->')
archivo_start = content.find('    <!-- Archivo Sonoro Section -->')

if historia_start != -1 and noticias_start != -1 and archivo_start != -1:
    part1 = content[:historia_start]
    part2_historia = content[historia_start:noticias_start]
    part3_noticias = content[noticias_start:archivo_start]
    part4 = content[archivo_start:]

    part3_noticias = part3_noticias.replace('Últimas Noticias', 'Noticias y Eventos')
    
    new_content = part1 + part3_noticias + part2_historia + part4
    
    # Hero size changes
    new_content = new_content.replace('class=\"relative min-h-[70vh] flex items-center justify-center overflow-hidden pt-10\"', 'class=\"relative min-h-[40vh] flex items-center justify-center overflow-hidden py-8\"')
    new_content = new_content.replace('class=\"relative z-10 text-center max-w-4xl px-6 mt-10\"', 'class=\"relative z-10 text-center max-w-4xl px-6 mt-4\"')
    new_content = new_content.replace('w-24 h-24 md:w-32 md:h-32', 'w-16 h-16 md:w-24 md:h-24')
    new_content = new_content.replace('text-5xl md:text-7xl font-extrabold', 'text-4xl md:text-5xl font-extrabold')
    new_content = new_content.replace('text-lg md:text-2xl text-gray-300 mb-10', 'text-base md:text-lg text-gray-300 mb-4')
    new_content = new_content.replace('mb-6 rounded-full border', 'mb-4 rounded-full border')
    new_content = new_content.replace('gap-6 md:gap-8 mb-6', 'gap-4 md:gap-6 mb-4')

    with io.open('resources/views/welcome.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print('Done')
else:
    print('Error finding sections')