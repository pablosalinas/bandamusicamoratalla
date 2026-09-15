import sys
import os
import re
import io
import json
import threading
import tkinter as tk
from tkinter import filedialog, messagebox, ttk
import fitz  # PyMuPDF

try:
    import pytesseract
    from PIL import Image
    TESSERACT_AVAILABLE = True
except Exception:
    TESSERACT_AVAILABLE = False

INSTRUMENTS = [
    {"id": 1, "name": "SAXOFÓN TENOR"},
    {"id": 2, "name": "FLAUTÍN"},
    {"id": 3, "name": "FLAUTA"},
    {"id": 4, "name": "OBOE"},
    {"id": 5, "name": "CORNO INGLÉS"},
    {"id": 6, "name": "FAGOT"},
    {"id": 7, "name": "REQUINTO"},
    {"id": 8, "name": "CLARINETE"},
    {"id": 9, "name": "CLARINETE BAJO"},
    {"id": 10, "name": "SAXOFÓN SOPRANO"},
    {"id": 11, "name": "SAXOFÓN ALTO"},
    {"id": 12, "name": "SAXOFÓN BARÍTONO"},
    {"id": 13, "name": "TROMPA"},
    {"id": 14, "name": "TROMPETA"},
    {"id": 15, "name": "FLISCORNO"},
    {"id": 16, "name": "TROMBÓN"},
    {"id": 17, "name": "TROMBÓN BAJO"},
    {"id": 18, "name": "BOMBARDINO"},
    {"id": 19, "name": "TUBA"},
    {"id": 20, "name": "VIOLONCHELO"},
    {"id": 21, "name": "CONTRABAJO"},
    {"id": 22, "name": "CAJA"},
    {"id": 23, "name": "BOMBO"},
    {"id": 24, "name": "PLATOS"},
    {"id": 25, "name": "TIMBALES"},
    {"id": 26, "name": "XILÓFONO"},
    {"id": 27, "name": "LIRA / GLOCKENSPIEL"},
    {"id": 28, "name": "MARIMBA"},
    {"id": 29, "name": "VIBRÁFONO"},
    {"id": 30, "name": "CAMPANAS TUBULARES"},
    {"id": 31, "name": "PANDERETA"},
    {"id": 32, "name": "TRIÁNGULO"},
    {"id": 33, "name": "CASTAÑUELAS"},
    {"id": 34, "name": "BATERÍA"},
    {"id": 35, "name": "PIANO"},
    {"id": 36, "name": "ARPA"}
]

TONE_MAPPINGS = [
    (r'\b(do)\b', 'do', r'\b(c)\b', 'c'),
    (r'\b(re)\b', 're', r'\b(d)\b', 'd'),
    (r'\b(mi\s*b|mib|mi\s*bemol)\b', 'mib', r'\b(eb|e\s*flat|e\s*b)\b', 'eb'),
    (r'\b(mi)\b', 'mi', r'\b(e)\b', 'e'),
    (r'\b(fa)\b', 'fa', r'\b(f)\b', 'f'),
    (r'\b(sol)\b', 'sol', r'\b(g)\b', 'g'),
    (r'\b(la\s*b|lab|la\s*bemol)\b', 'lab', r'\b(ab|a\s*flat|a\s*b)\b', 'ab'),
    (r'\b(la)\b', 'la', r'\b(a)\b', 'a'),
    (r'\b(si\s*b|sib|si\s*bemol)\b', 'sib', r'\b(bb|b\s*flat|b\s*b)\b', 'bb'),
    (r'\b(si)\b', 'si', r'\b(b)\b', 'b')
]

def normalize_text(text):
    import unicodedata
    return unicodedata.normalize('NFD', text).encode('ascii', 'ignore').decode('utf-8').lower()

def expand_aliases(inst_name):
    inst_normalized = normalize_text(inst_name)
    inst_aliases = [inst_normalized]
    
    if 'flautin' in inst_normalized or 'piccolo' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('flautin', 'piccolo'))
    if 'flauta' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('flauta', 'flute'))
    if 'oboe' in inst_normalized:
        inst_aliases.append('oboe')
    if 'fagot' in inst_normalized:
        inst_aliases.extend(['bassoon', 'fagotto'])
    if 'requinto' in inst_normalized:
        inst_aliases.extend(['eb clarinet', 'requinto'])
    if 'trompa' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('trompa', 'tompa'))
        inst_aliases.append(inst_normalized.replace('trompa', 'horn'))
        inst_aliases.extend(['french horn', 'corno'])
    if 'bombardino' in inst_normalized:
        inst_aliases.extend([
            inst_normalized.replace('bombardino', 'bombardin'),
            inst_normalized.replace('bombardino', 'eufonio'),
            inst_normalized.replace('bombardino', 'euphonium'),
            inst_normalized.replace('bombardino', 'eufonium')
        ])
    if 'tuba' in inst_normalized:
        inst_aliases.extend(['tuba', 'bajo', 'bajos', 'bass', 'basses'])
    if 'corno ingles' in inst_normalized:
        inst_aliases.append('english horn')
        inst_aliases.append('cor anglais')
    if 'saxofon' in inst_normalized or 'saxo' in inst_normalized:
        s = inst_normalized.replace('saxofones', 'saxos').replace('saxofon', 'saxo')
        inst_aliases.append(s)
        inst_aliases.append(inst_normalized.replace('saxofones', 'saxophones').replace('saxofon', 'saxophone'))
        inst_aliases.append(inst_normalized.replace('saxofones', 'saxes').replace('saxofon', 'sax'))
    if 'clarinete' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('clarinete', 'clarinet'))
    if 'violonchelo' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('violonchelo', 'violoncel'))
        inst_aliases.append(inst_normalized.replace('violonchelo', 'cello'))
        inst_aliases.append(inst_normalized.replace('violonchelo', 'violoncello'))
    if 'contrabajo' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('contrabajo', 'contrabaix'))
        inst_aliases.append(inst_normalized.replace('contrabajo', 'double bass'))
        inst_aliases.append(inst_normalized.replace('contrabajo', 'string bass'))
        inst_aliases.append('acoustic bass')
    if 'fliscorno' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('fliscorno', 'fiscorn'))
        inst_aliases.append(inst_normalized.replace('fliscorno', 'flugelhorn'))
        inst_aliases.append(inst_normalized.replace('fliscorno', 'flugel'))
    if 'trompeta' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('trompeta', 'trumpet'))
    if 'trombon' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('trombon', 'trombone'))
    if 'platillos' in inst_normalized or 'platos' in inst_normalized:
        inst_aliases.append('plats')
        inst_aliases.append('platerets')
        inst_aliases.append('cymbals')
        inst_aliases.append('piatti')
    if 'caja' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('caja', 'caixa'))
        inst_aliases.append('snare drum')
    if 'bombo' in inst_normalized:
        inst_aliases.append('bass drum')
        inst_aliases.append('gran cassa')
    if 'timbales' in inst_normalized:
        inst_aliases.append('timpani')
    if 'campanas' in inst_normalized:
        inst_aliases.append('chimes')
        inst_aliases.append('tubular bells')
        inst_aliases.append('campanelli')
        
    expanded_aliases = []
    for alias in inst_aliases:
        expanded_aliases.append(alias)
        no_en = re.sub(r'\ben\b', '', alias).strip()
        no_en = re.sub(r'\s+', ' ', no_en)
        if no_en != alias:
            expanded_aliases.append(no_en)
            
        for es_rgx, es_str, en_rgx, en_str in TONE_MAPPINGS:
            if re.search(es_rgx, alias):
                mod = re.sub(es_rgx, en_str, alias)
                mod = re.sub(r'\ben\b', '', mod).strip()
                expanded_aliases.append(re.sub(r'\s+', ' ', mod))
            elif re.search(en_rgx, alias):
                mod = re.sub(en_rgx, es_str, alias)
                mod = re.sub(r'\ben\b', '', mod).strip()
                expanded_aliases.append(re.sub(r'\s+', ' ', mod))
                
    final_aliases = []
    for a in expanded_aliases:
        a = re.sub(r'\s+', ' ', a).strip()
        if a not in final_aliases:
            final_aliases.append(a)
    return final_aliases

def detect_instrument(text, instruments, sorted_instruments):
    text_norm = normalize_text(text)
    best_match = None
    best_alias = None
    
    for inst in sorted_instruments:
        aliases = expand_aliases(inst['name'])
        for alias in aliases:
            parts = alias.split()
            all_parts_found = True
            for part in parts:
                if not re.search(r'\b' + re.escape(part) + r'\b', text_norm):
                    all_parts_found = False
                    break
            
            if all_parts_found:
                best_match = inst
                best_alias = alias
                break
        if best_match:
            break
            
    if best_match:
        inst_type = "TODOS"
        
        if "principal" in text_norm or "solo" in text_norm:
            inst_type = "PRINCIPAL"
        else:
            # Buscar el tipo (1, 2, 3, I, II, etc.) justo DESPUÉS del nombre del instrumento (en un rango de 15 caracteres)
            # para evitar confundirlo con números de página sueltos en el encabezado.
            # Como el alias puede estar dividido en parts, buscamos la última parte del alias
            last_part = best_alias.split()[-1]
            alias_match = re.search(r'\b' + re.escape(last_part) + r'\b', text_norm)
            
            if alias_match:
                start_match = alias_match.start()
                end_match = alias_match.end()
                
                # Contexto para buscar tipo
                substring_after = text_norm[end_match:end_match+40]
                type_m = re.search(r'\b([1-4]|i{1,3}|iv)\s*(o|º|ero|undo|ercero|uarto)?\b', substring_after)
                if type_m:
                    val = type_m.group(1)
                    roman_to_num = {'i': '1', 'ii': '2', 'iii': '3', 'iv': '4'}
                    val = roman_to_num.get(val, val)
                    inst_type = val + "º"
                    
                # Contexto para buscar tonalidad (antes y después)
                start_search = max(0, start_match - 30)
                end_search = min(len(text_norm), end_match + 40)
                context_string = text_norm[start_search:end_search]
                
                # Expresión regular para tonalidades (añadido b bemol, etc)
                ton_regex = r'\b([a-g](?:\s*b|\s*bemol|\s*#)?|do|re|mi|fa|sol|la|si(?:\s*bemol|\s*b)?)\b'
                
                # Buscar formato 1: "en Si bemol", "in Bb"
                is_format_1 = False
                ton_match = re.search(r'\b(en|in)\s+' + ton_regex, context_string)
                if ton_match:
                    is_format_1 = True
                
                # Buscar formato 2: "C Tuba", "B bemol Tuba" (tonalidad justo antes del alias)
                if not ton_match:
                    ton_match = re.search(ton_regex + r'\s+' + re.escape(best_alias), context_string)
                
                if ton_match:
                    # El grupo capturado dependerá de qué regex coincidió
                    t_raw = ton_match.group(2) if is_format_1 else ton_match.group(1)
                    t_raw = t_raw.replace(" ", "").lower()
                    t_map = {
                        'c': 'Do', 'do': 'Do',
                        'd': 'Re', 're': 'Re',
                        'eb': 'Mi_bemol', 'mib': 'Mi_bemol', 'mibemol': 'Mi_bemol', 'ebemol': 'Mi_bemol',
                        'e': 'Mi', 'mi': 'Mi',
                        'f': 'Fa', 'fa': 'Fa',
                        'g': 'Sol', 'sol': 'Sol',
                        'ab': 'La_bemol', 'lab': 'La_bemol', 'labemol': 'La_bemol', 'abemol': 'La_bemol',
                        'a': 'La', 'la': 'La',
                        'bb': 'Si_bemol', 'sib': 'Si_bemol', 'sibemol': 'Si_bemol', 'bbemol': 'Si_bemol',
                        'b': 'Si', 'si': 'Si'
                    }
                    ton_std = t_map.get(t_raw)
                    if ton_std:
                        tonality = f"en {ton_std}"
                        return best_match['id'], best_match['name'], inst_type, tonality
            
        return best_match['id'], best_match['name'], inst_type, ""
        
    return None, None, None, ""

class PdfSplitterApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Divisor de Partituras Automático - Banda de Música de Moratalla")
        self.root.geometry("650x400")
        
        self.pdf_path = tk.StringVar()
        self.output_dir = tk.StringVar()
        
        self.config_file = "divisor_config.json"
        self.load_config()
        
        self.setup_ui()
        
    def load_config(self):
        if os.path.exists(self.config_file):
            try:
                with open(self.config_file, 'r', encoding='utf-8') as f:
                    config = json.load(f)
                    if config.get("last_pdf_path"):
                        self.pdf_path.set(config.get("last_pdf_path"))
                    if config.get("last_output_dir"):
                        self.output_dir.set(config.get("last_output_dir"))
            except:
                pass

    def save_config(self):
        config = {
            "last_pdf_path": self.pdf_path.get(),
            "last_output_dir": self.output_dir.get()
        }
        try:
            with open(self.config_file, 'w', encoding='utf-8') as f:
                json.dump(config, f, indent=4)
        except:
            pass
        
    def setup_ui(self):
        frame = tk.Frame(self.root, padx=20, pady=20)
        frame.pack(fill=tk.BOTH, expand=True)
        
        tk.Label(frame, text="1. Selecciona el PDF General:", font=("Arial", 10, "bold")).pack(anchor="w", pady=(0, 5))
        
        pdf_frame = tk.Frame(frame)
        pdf_frame.pack(fill=tk.X, pady=(0, 15))
        tk.Entry(pdf_frame, textvariable=self.pdf_path, width=60, state='readonly').pack(side=tk.LEFT, fill=tk.X, expand=True)
        tk.Button(pdf_frame, text="Explorar...", command=self.browse_pdf).pack(side=tk.LEFT, padx=(10, 0))
        
        tk.Label(frame, text="2. Selecciona la carpeta de destino:", font=("Arial", 10, "bold")).pack(anchor="w", pady=(0, 5))
        
        out_frame = tk.Frame(frame)
        out_frame.pack(fill=tk.X, pady=(0, 20))
        tk.Entry(out_frame, textvariable=self.output_dir, width=60, state='readonly').pack(side=tk.LEFT, fill=tk.X, expand=True)
        tk.Button(out_frame, text="Explorar...", command=self.browse_output).pack(side=tk.LEFT, padx=(10, 0))
        
        self.progress_var = tk.DoubleVar()
        self.progress = ttk.Progressbar(frame, variable=self.progress_var, maximum=100)
        self.progress.pack(fill=tk.X, pady=(10, 5))
        
        self.status_label = tk.Label(frame, text="Listo para procesar.", fg="gray")
        self.status_label.pack(anchor="w")
        
        self.process_btn = tk.Button(frame, text="Dividir Particellas", bg="#4CAF50", fg="white", font=("Arial", 12, "bold"), command=self.start_processing)
        self.process_btn.pack(pady=20, fill=tk.X)
        
    def browse_pdf(self):
        path = filedialog.askopenfilename(filetypes=[("Archivos PDF", "*.pdf")])
        if path:
            self.pdf_path.set(path)
            
    def browse_output(self):
        path = filedialog.askdirectory()
        if path:
            self.output_dir.set(path)
            
    def start_processing(self):
        if not self.pdf_path.get():
            messagebox.showinfo("Información", "Por favor, selecciona el PDF origen que deseas dividir.")
            self.browse_pdf()
        if not self.output_dir.get():
            messagebox.showinfo("Información", "Por favor, selecciona la carpeta destino donde se guardarán las partituras.")
            self.browse_output()
            
        pdf = self.pdf_path.get()
        out = self.output_dir.get()
        
        if not pdf or not out:
            messagebox.showerror("Error", "Debes seleccionar un PDF y una carpeta de destino para continuar.")
            return
            
        self.save_config()
        self.process_btn.config(state=tk.DISABLED)
        self.progress_var.set(0)
        self.status_label.config(text="Procesando... (Puede tardar unos minutos)")
        
        # Ejecutar en hilo separado para no bloquear la UI
        threading.Thread(target=self.process_pdf, args=(pdf, out), daemon=True).start()
        
    def process_pdf(self, pdf_path, output_dir):
        try:
            doc = fitz.open(pdf_path)
            total_pages = len(doc)
            
            # Ordenar instrumentos por longitud de nombre descendente
            sorted_instruments = sorted(INSTRUMENTS, key=lambda x: len(x['name']), reverse=True)
            
            splits = []
            current_split = None
            
            for page_num in range(total_pages):
                self.progress_var.set((page_num / total_pages) * 50)
                self.status_label.config(text=f"Analizando página {page_num + 1} de {total_pages}...")
                
                page = doc[page_num]
                rect = page.rect
                # Analizar solo el 15% superior de la página (encabezado) para evitar leer texto de la partitura
                top_part = fitz.Rect(0, 0, rect.width, rect.height * 0.15)
                text = page.get_text("text", clip=top_part)
                
                if len(text.strip()) < 5 and TESSERACT_AVAILABLE:
                    pix = page.get_pixmap(clip=top_part, matrix=fitz.Matrix(2, 2))
                    img_data = pix.tobytes("png")
                    img = Image.open(io.BytesIO(img_data))
                    text = pytesseract.image_to_string(img, lang='spa+eng')
                    
                if len(text.strip()) < 2:
                    if current_split:
                        current_split['pages'].append(page_num)
                    continue
                    
                inst_id, inst_name, inst_type, tonality = detect_instrument(text, INSTRUMENTS, sorted_instruments)
                
                if inst_id:
                    if current_split and current_split['instrument_id'] == inst_id:
                        is_same_type = (current_split['type'] == inst_type)
                        is_missing_type = (inst_type == "TODOS")
                        is_page_number = (inst_type == f"{len(current_split['pages']) + 1}º")
                        # Si el tipo anterior era TODOS y ahora detecta un número, podría ser fallo del OCR en la pág 1.
                        # Por seguridad, si es el mismo instrumento, tendemos a agrupar salvo que sea un salto claro (ej de 1º a 2º)
                        is_upgrade_from_todos = (current_split['type'] == "TODOS" and inst_type != "TODOS")
                        
                        # Si ambas tienen tonalidad y es DISTINTA, entonces NO agrupar
                        tonality_mismatch = bool(current_split.get('tonality') and tonality and current_split['tonality'] != tonality)
                        
                        if not tonality_mismatch and (is_same_type or is_missing_type or is_page_number or is_upgrade_from_todos):
                            current_split['pages'].append(page_num)
                            if not current_split.get('tonality') and tonality:
                                current_split['tonality'] = tonality
                            # Si era TODOS y ahora sabemos el tipo, lo actualizamos
                            if current_split['type'] == "TODOS" and inst_type != "TODOS":
                                current_split['type'] = inst_type
                        else:
                            current_split = {
                                'instrument_id': inst_id,
                                'instrument_name': inst_name,
                                'type': inst_type,
                                'tonality': tonality,
                                'pages': [page_num]
                            }
                            splits.append(current_split)
                    else:
                        current_split = {
                            'instrument_id': inst_id,
                            'instrument_name': inst_name,
                            'type': inst_type,
                            'tonality': tonality,
                            'pages': [page_num]
                        }
                        splits.append(current_split)
                else:
                    if current_split:
                        current_split['pages'].append(page_num)
                    else:
                        current_split = {
                            'instrument_id': None,
                            'instrument_name': 'Desconocido',
                            'type': 'TODOS',
                            'tonality': '',
                            'pages': [page_num]
                        }
                        splits.append(current_split)
                        
            # Generar PDFs
            total_splits = len(splits)
            counters = {}
            for i, split in enumerate(splits):
                self.progress_var.set(50 + (i / total_splits) * 50)
                self.status_label.config(text=f"Guardando particella {i + 1} de {total_splits}...")
                
                if not split['pages']:
                    continue
                    
                new_doc = fitz.open()
                for p in split['pages']:
                    new_doc.insert_pdf(doc, from_page=p, to_page=p)
                    
                if split['instrument_id'] is None:
                    base_name = f"Desconocido_Paginas_{split['pages'][0]+1}"
                else:
                    base_name = split['instrument_name'].replace("/", "-").replace("\\", "-")
                    if split['type'] != "TODOS":
                        base_name += f"_{split['type']}"
                    
                    if split.get('tonality'):
                        # Se reemplazan espacios por guiones bajos para el nombre de archivo
                        safe_tonality = split['tonality'].replace(" ", "_")
                        base_name += f"_{safe_tonality}"
                        
                # Prevenir sobreescribir si hay varios del mismo tipo
                if base_name not in counters:
                    counters[base_name] = 1
                    final_name = base_name
                else:
                    counters[base_name] += 1
                    final_name = f"{base_name}_{counters[base_name]}"
                    
                out_path = os.path.join(output_dir, f"{final_name}.pdf")
                new_doc.save(out_path)
                new_doc.close()
                
            doc.close()
            
            self.progress_var.set(100)
            self.status_label.config(text="¡Proceso completado con éxito!")
            messagebox.showinfo("Éxito", f"Se han extraído {len(splits)} particellas en la carpeta seleccionada.")
            
        except Exception as e:
            messagebox.showerror("Error", f"Ha ocurrido un error inesperado:\n{str(e)}")
            self.status_label.config(text="Error durante el proceso.")
        finally:
            self.process_btn.config(state=tk.NORMAL)

if __name__ == "__main__":
    root = tk.Tk()
    app = PdfSplitterApp(root)
    root.mainloop()
