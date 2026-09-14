import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\welcome.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

# 1. first one
c = c.replace(
    '''<div class="absolute bottom-4 left-0 right-0 mx-auto w-11/12 text-center">''',
    '''<div class="absolute top-4 left-0 right-0 mx-auto w-11/12 text-center z-[110]">'''
)

# 2. second one
c = c.replace(
    '''<div class="absolute bottom-10 left-0 right-0 mx-auto w-11/12 max-w-3xl text-center">''',
    '''<div class="absolute top-10 left-0 right-0 mx-auto w-11/12 max-w-3xl text-center z-[110]">'''
)

# 3. third one
c = c.replace(
    '''<p x-show="slide.desc" class="absolute bottom-4 text-white text-sm md:text-base bg-black/70 px-4 py-1.5 rounded-full backdrop-blur-sm" x-text="slide.desc"></p>''',
    '''<p x-show="slide.desc" class="absolute top-4 text-white text-sm md:text-base bg-black/70 px-4 py-1.5 rounded-full backdrop-blur-sm z-[110]" x-text="slide.desc"></p>'''
)

# 4. For Historia, mt-4 pushes it below the video, but if it overflows, maybe top is better. Let's make it absolute top as well.
c = c.replace(
    '''<p x-show="slide.desc" class="mt-4 text-white text-base md:text-lg font-medium text-center bg-black/70 px-6 py-2 rounded-full backdrop-blur-sm" x-text="slide.desc"></p>''',
    '''<p x-show="slide.desc" class="absolute top-6 text-white text-base md:text-lg font-medium text-center bg-black/70 px-6 py-2 rounded-full backdrop-blur-sm z-[110]" x-text="slide.desc"></p>'''
)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)
print('Positions updated')