import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

# 1. Proposing new instruments in uppercase
old_prop = '''                                part = part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
                                if (part.toLowerCase() === 'guitarra' || part.toLowerCase() === 'guitarra espanola') {
                                    part = 'Guitarra española';
                                }'''
new_prop = '''                                if (part.toLowerCase() === 'guitarra' || part.toLowerCase() === 'guitarra espanola') {
                                    part = 'GUITARRA ESPAÑOLA';
                                } else {
                                    part = part.toUpperCase();
                                }'''
c = c.replace(old_prop, new_prop)

# 2. Deduplicating matched instruments case-insensitively
# Around line 421:
# if (!isSubset) {
#     matchedInstrumentsArr.push(inst);
#     matchedAliases.push(foundAlias);
# }
old_push = '''                              if (!isSubset) {
                                  matchedInstrumentsArr.push(inst);
                                  matchedAliases.push(foundAlias);
                              }'''
new_push = '''                              if (!isSubset) {
                                  // Evitar añadir duplicados exactos (ej. TROMPETA y Trompeta) de la BD
                                  let isDuplicate = false;
                                  for (let i = 0; i < matchedInstrumentsArr.length; i++) {
                                      if (matchedInstrumentsArr[i].name.toLowerCase() === inst.name.toLowerCase()) {
                                          isDuplicate = true;
                                          // Preferir la version en mayusculas si ya estaba la minuscula
                                          if (inst.name === inst.name.toUpperCase()) {
                                              matchedInstrumentsArr[i] = inst;
                                          }
                                          break;
                                      }
                                  }
                                  if (!isDuplicate) {
                                      matchedInstrumentsArr.push(inst);
                                      matchedAliases.push(foundAlias);
                                  }
                              }'''
c = c.replace(old_push, new_push)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)