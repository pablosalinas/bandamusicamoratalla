import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\welcome.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

card_old = '''<div class="text-xs text-amber-500 font-semibold tracking-wide uppercase mb-3">
                                    {{ ->created_at->format('d/m/Y') }}
                                </div>'''
card_new = '''@if(!->event_date)
                                    <div class="text-xs text-amber-500 font-semibold tracking-wide uppercase mb-3">
                                        {{ ->created_at->format('d/m/Y') }}
                                    </div>
                                @endif'''
c = c.replace(card_old, card_new)

modal_old = '''<span>📅 Publicado: {{ ->created_at->format('d/m/Y') }}</span>'''
modal_new = '''@if(!->event_date)
                                                        <span>📅 Publicado: {{ ->created_at->format('d/m/Y') }}</span>
                                                    @endif'''
c = c.replace(modal_old, modal_new)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)