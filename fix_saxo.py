import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

old_code = '''                        if (instNormalized.includes('clarinete')) instAliases.push(instNormalized.replace('clarinete', 'clarinet'));'''
new_code = '''                        if (instNormalized.includes('saxofon')) {
                            let s = instNormalized.replace(/saxofones/g, 'saxos').replace(/saxofon/g, 'saxo');
                            instAliases.push(s);
                            instAliases.push(instNormalized.replace(/saxofones/g, 'saxophones').replace(/saxofon/g, 'saxophone'));
                            instAliases.push(instNormalized.replace(/saxofones/g, 'saxes').replace(/saxofon/g, 'sax'));
                        }
                        if (instNormalized.includes('clarinete')) instAliases.push(instNormalized.replace('clarinete', 'clarinet'));'''

c = c.replace(old_code, new_code)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)
print('Done adding saxo aliases')