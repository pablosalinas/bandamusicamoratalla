import io
file_path = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with io.open(file_path, 'r', encoding='utf-8') as f:
    c = f.read()

# Fix 1: DB deduplication Canonical check
old_dedup = '''                                    if (existName === newName || matchedAliases[i] === foundAlias || matchedAliases[i].includes(foundAlias) || foundAlias.includes(matchedAliases[i])) {
                                        isDuplicate = true;
                                        if (inst.name === inst.name.toUpperCase() && existName === newName) {
                                            matchedInstrumentsArr[i] = inst;
                                        }
                                        break;
                                    }'''
new_dedup = '''                                    let canonicalExist = existName.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/saxofones/g, 'saxos').replace(/saxofon/g, 'saxo').replace(/eufonio|euphonium|bombardino/g, 'eufonium').replace(/\\btuba\\b/g, 'bajo').replace(/\\s+/g, ' ').trim();
                                    let canonicalNew = newName.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/saxofones/g, 'saxos').replace(/saxofon/g, 'saxo').replace(/eufonio|euphonium|bombardino/g, 'eufonium').replace(/\\btuba\\b/g, 'bajo').replace(/\\s+/g, ' ').trim();
                                    
                                    if (canonicalExist === canonicalNew || canonicalExist.includes(canonicalNew) || canonicalNew.includes(canonicalExist) || matchedAliases[i] === foundAlias || matchedAliases[i].includes(foundAlias) || foundAlias.includes(matchedAliases[i])) {
                                        isDuplicate = true;
                                        if (inst.name === inst.name.toUpperCase() && canonicalExist === canonicalNew) {
                                            matchedInstrumentsArr[i] = inst;
                                        }
                                        break;
                                    }'''
c = c.replace(old_dedup, new_dedup)

# Fix 2: Proposal filter for numbers/symbols
old_prop = '''                            if (part.length > 2) {
                                if (part.toLowerCase() === 'guitarra' || part.toLowerCase() === 'guitarra espanola' || part.toLowerCase() === 'guitarra española') {'''
new_prop = '''                            let alphaOnly = part.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ]/g, '').toLowerCase();
                            if (part.length > 2 && alphaOnly.length > 2 && !/^(uno|dos|tres|cuatro|cinco|seis)$/.test(alphaOnly) && !/^(iii|iv|v|vi|vii|viii|ix|x)$/.test(alphaOnly)) {
                                if (part.toLowerCase() === 'guitarra' || part.toLowerCase() === 'guitarra espanola' || part.toLowerCase() === 'guitarra española') {'''
c = c.replace(old_prop, new_prop)

# Also fix the regex in type removal to handle commas after the type!
# (?:^|\s) ... (?:\s|$)  ->  (?:^|\s|,) ... (?:\s|,|$)
old_type_regex = '''/(?:^|\\s)(?:1(?:st|o|a|er|º|ª)?|2(?:nd|o|a|do|º|ª)?|3(?:rd|o|a|er|ro|º|ª)?|4(?:th|o|a|to|º|ª)?|i|ii|iii|iv)(?:\\s|$)/gi'''
new_type_regex = '''/(?:^|\\s|,|_|-)(?:1(?:st|o|a|er|º|ª)?|2(?:nd|o|a|do|º|ª)?|3(?:rd|o|a|er|ro|º|ª)?|4(?:th|o|a|to|º|ª)?|i|ii|iii|iv)(?:\\s|,|_|-|$)/gi'''
c = c.replace(old_type_regex, new_type_regex)

with io.open(file_path, 'w', encoding='utf-8') as f:
    f.write(c)
print('Applied final bugs fixes')