import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

old_logic = '''                          for (let part of leftoverParts) {
                              part = part.trim();
                              let alphaOnly = part.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ]/g, '').toLowerCase();'''
new_logic = '''                          for (let part of leftoverParts) {
                              // Quitar extensiones repetidas y parentesis vacios/restantes
                              part = part.replace(/\.(pdf|jpg|jpeg|png|webp|bmp)/gi, '').replace(/[()[\]{}_-]/g, ' ').replace(/\s+/g, ' ').trim();
                              let alphaOnly = part.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ]/g, '').toLowerCase();'''
c = c.replace(old_logic, new_logic)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)
print('Applied junk remover logic to proposal')