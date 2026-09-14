import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

old_logic = '''                            alias = alias.replace(/\s+/g, ' ').trim();
                            if (fileNormalized.includes(alias)) {
                                found = true; foundAlias = alias; break;
                            }'''

new_logic = '''                            alias = alias.replace(/\s+/g, ' ').trim();
                            if (fileNormalized.includes(alias)) {
                                if (alias === 'bajo' && fileNormalized.includes('contrabajo')) {
                                    // skip "bajo" if it's actually "contrabajo"
                                } else if (alias === 'bass' && fileNormalized.includes('contrabass')) {
                                    // skip
                                } else {
                                    found = true; foundAlias = alias; break;
                                }
                            }'''

c = c.replace(old_logic, new_logic)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)