import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

old_logic = '''                                  let isAlreadyMatched = false;
                                  for(let m of matchedInstrumentsArr) {
                                      let mNorm = m.originalName.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                                      let pNorm = part.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                                      if(mNorm.includes(pNorm) || pNorm.includes(mNorm)) {
                                          isAlreadyMatched = true; 
                                          break;
                                      }
                                  }'''
new_logic = '''                                  let isAlreadyMatched = false;
                                  for(let m of matchedInstrumentsArr) {
                                      let mNorm = m.originalName.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                                      let pNorm = part.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                                      
                                      let canonicalM = mNorm.replace(/saxofones/g, 'saxos').replace(/saxofon/g, 'saxo').replace(/eufonio|euphonium|bombardino/g, 'eufonium').replace(/\\btuba\\b/g, 'bajo');
                                      let canonicalP = pNorm.replace(/saxofones/g, 'saxos').replace(/saxofon/g, 'saxo').replace(/eufonio|euphonium|bombardino/g, 'eufonium').replace(/\\btuba\\b/g, 'bajo');
                                      
                                      if(canonicalM.includes(canonicalP) || canonicalP.includes(canonicalM)) {
                                          isAlreadyMatched = true; 
                                          break;
                                      }
                                  }'''
c = c.replace(old_logic, new_logic)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)
print('Applied canonical fix to proposal loop')