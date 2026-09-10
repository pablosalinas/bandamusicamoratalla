import os

filepath = r'c:\xampp_2023\htdocs\bandamusicamoratalla\resources\views\admin\sheet-music\edit.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    text = f.read()

idx = text.find('// FALTANTES')
if idx == -1:
    print('NOT FOUND')
    exit(1)

new_js = '''                        let matchedType = "TODOS"; 
                        if (fileNormalized.match(/(?:^|\\s)1(?:st|o|a|er|\\s|$)/) || fileNormalized.includes("primero") || fileNormalized.includes("primera")) {
                            matchedType = "1º";
                        } else if (fileNormalized.match(/(?:^|\\s)2(?:nd|o|a|do|\\s|$)/) || fileNormalized.includes("segundo") || fileNormalized.includes("segunda")) {
                            matchedType = "2º";
                        } else if (fileNormalized.match(/(?:^|\\s)3(?:rd|o|a|er|\\s|$)/) || fileNormalized.includes("tercero") || fileNormalized.includes("tercera")) {
                            matchedType = "3º";
                        } else if (fileNormalized.includes("principal") || fileNormalized.includes("pral") || fileNormalized.match(/\\bsolo\\b/)) {
                            matchedType = "PRINCIPAL";
                        }
                        
                        let uuid = "file_" + Math.random().toString(36).substr(2, 9);
                        let tr = document.createElement("tr");
                        
                        let tdFile = document.createElement("td");
                        tdFile.className = "py-3 pl-3 pr-3 text-xs font-medium text-white break-all";
                        tdFile.innerHTML = "\\n" +
                            file.name + "\\n" +
                            "<input type=\\"file\\" id=\\"input_" + uuid + "\\" name=\\"smart_grid_files[" + uuid + "]\\" class=\\"hidden\\">\\n";
                        tr.appendChild(tdFile);
                        
                        for(let col = 0; col < 3; col++) {
                            let td = document.createElement("td");
                            td.className = "py-2 px-2";
                            
                            let instName = "";
                            let typeName = "";
                            
                            if (matchedInstrumentsArr[col]) {
                                instName = matchedInstrumentsArr[col].originalName;
                                typeName = matchedType;
                            }
                            
                            td.innerHTML = "\\n" +
                                "<div class=\\"flex flex-col gap-1\\">\\n" +
                                    "<input type=\\"text\\" list=\\"instrument_catalog_list\\" name=\\"smart_grid_instruments[" + uuid + "][]\\" value=\\"" + instName + "\\" class=\\"bg-gray-800 text-xs text-white rounded border border-gray-600 px-2 py-1.5 w-full focus:ring-indigo-500 focus:border-indigo-500\\" placeholder=\\"Escribir...\\">\\n" +
                                    "<select name=\\"smart_grid_types[" + uuid + "][]\\" class=\\"bg-gray-800 text-xs text-white rounded border border-gray-600 px-2 py-1 w-full focus:ring-indigo-500 focus:border-indigo-500\\">\\n" +
                                        "<option value=\\"\\">- Tipo -</option>\\n" +
                                        "<option value=\\"TODOS\\" " + (typeName==='TODOS'?'selected':'') + ">TODOS</option>\\n" +
                                        "<option value=\\"1º\\" " + (typeName==='1º'?'selected':'') + ">1º</option>\\n" +
                                        "<option value=\\"2º\\" " + (typeName==='2º'?'selected':'') + ">2º</option>\\n" +
                                        "<option value=\\"3º\\" " + (typeName==='3º'?'selected':'') + ">3º</option>\\n" +
                                        "<option value=\\"PRINCIPAL\\" " + (typeName==='PRINCIPAL'?'selected':'') + ">PRINCIPAL</option>\\n" +
                                    "</select>\\n" +
                                "</div>\\n";
                            tr.appendChild(td);
                        }
                        
                        document.getElementById("smart_grid_container").classList.remove("hidden");
                        document.getElementById("smart_grid_body").appendChild(tr);
                        
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        document.getElementById("input_" + uuid).files = dt.files;
                    }
                });
            }
        });
    </script>
</x-admin-layout>
'''

new_text = text[:idx] + new_js
with open(filepath, 'w', encoding='utf-8') as f:
    f.write(new_text)

print('Done')
