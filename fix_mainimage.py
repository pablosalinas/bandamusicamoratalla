import re
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\welcome.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

old_block = '''                            @if(->mainImage)
                                <div class="h-48 w-full overflow-hidden relative">
                                    <img src="{{ ->mainImage->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                                </div>
                            @endif'''

new_block = '''                            @if(->mainImage)
                                @php
                                    \ = strtolower(pathinfo(\->mainImage->url, PATHINFO_EXTENSION));
                                    \ = in_array(\, ['mp4', 'mov', 'webm', 'avi']);
                                @endphp
                                <div class="h-48 w-full overflow-hidden relative">
                                    @if(\)
                                        <video src="{{ \->mainImage->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" muted loop autoplay playsinline></video>
                                    @else
                                        <img src="{{ \->mainImage->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @endif
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                                </div>
                            @endif'''

c = c.replace(old_block, new_block)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)