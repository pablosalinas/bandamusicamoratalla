import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

old_bombardino = '''if (instNormalized.includes('bombardino')) {
                            instAliases.push(instNormalized.replace('bombardino', 'bombardin'));
                            instAliases.push(instNormalized.replace('bombardino', 'eufonio'));
                            instAliases.push(instNormalized.replace('bombardino', 'euphonium'));
                        }'''
new_bombardino = '''if (instNormalized.includes('bombardino')) {
                            instAliases.push(instNormalized.replace('bombardino', 'bombardin'));
                            instAliases.push(instNormalized.replace('bombardino', 'eufonio'));
                            instAliases.push(instNormalized.replace('bombardino', 'euphonium'));
                            instAliases.push(instNormalized.replace('bombardino', 'eufonium'));
                        }'''
c = c.replace(old_bombardino, new_bombardino)

old_tuba = '''if (instNormalized.includes('tuba') && instNormalized.includes('do')) instAliases.push('tuba');'''
new_tuba = '''if (instNormalized.includes('tuba') && instNormalized.includes('do')) {
                            instAliases.push('tuba');
                            instAliases.push('bajo');
                            instAliases.push('bajos');
                            instAliases.push('bass');
                            instAliases.push('basses');
                        }'''
c = c.replace(old_tuba, new_tuba)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)