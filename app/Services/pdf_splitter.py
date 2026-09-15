import sys
import json
try:
    import pymupdf as fitz
except ImportError:
    import fitz
import re
import os
import io

try:
    import pytesseract
    from PIL import Image
    TESSERACT_AVAILABLE = True
except Exception:
    TESSERACT_AVAILABLE = False

# Mapeos de tonos
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
    
    if 'trompa' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('trompa', 'tompa'))
        inst_aliases.append(inst_normalized.replace('trompa', 'horn'))
        if 'fa' in inst_normalized:
            inst_aliases.extend(['trompa', 'trompas', 'horn', 'french horn', 'corno'])
    
    if 'bombardino' in inst_normalized:
        inst_aliases.extend([
            inst_normalized.replace('bombardino', 'bombardin'),
            inst_normalized.replace('bombardino', 'eufonio'),
            inst_normalized.replace('bombardino', 'euphonium'),
            inst_normalized.replace('bombardino', 'eufonium')
        ])
        if 'do' in inst_normalized:
            inst_aliases.extend(['bombardino', 'bombardin', 'eufonio', 'euphonium', 'eufonium'])
            
    if 'tuba' in inst_normalized and 'do' in inst_normalized:
        inst_aliases.extend(['tuba', 'bajo', 'bajos', 'bass', 'basses'])
        
    if 'saxofon' in inst_normalized:
        s = inst_normalized.replace('saxofones', 'saxos').replace('saxofon', 'saxo')
        inst_aliases.append(s)
        inst_aliases.append(inst_normalized.replace('saxofones', 'saxophones').replace('saxofon', 'saxophone'))
        inst_aliases.append(inst_normalized.replace('saxofones', 'saxes').replace('saxofon', 'sax'))
        
    if 'clarinete' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('clarinete', 'clarinet'))
    if 'violonchelo' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('violonchelo', 'violoncel'))
        inst_aliases.append(inst_normalized.replace('violonchelo', 'cello'))
    if 'contrabajo' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('contrabajo', 'contrabaix'))
    if 'fliscorno' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('fliscorno', 'fiscorn'))
    if 'platillos' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('platillos', 'plats'))
        inst_aliases.append(inst_normalized.replace('platillos', 'platerets'))
    if 'caja' in inst_normalized:
        inst_aliases.append(inst_normalized.replace('caja', 'caixa'))
        
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
    best_length = 0
    
    for inst in sorted_instruments:
        aliases = inst['aliases']
        for alias in aliases:
            if alias in text_norm:
                if alias == 'bajo' and 'contrabajo' in text_norm:
                    continue
                if alias == 'bass' and 'contrabass' in text_norm:
                    continue
                if alias == 'corno' and ('fliscorno' in text_norm or 'fiscorn' in text_norm or 'corno ingles' in text_norm):
                    continue
                    
                if len(alias) > best_length:
                    best_match = inst
                    best_length = len(alias)
                    
            parts = alias.split(' ')
            if len(parts) > 1:
                all_found = True
                for p in parts:
                    if not re.search(r'\b' + re.escape(p) + r'\b', text_norm):
                        all_found = False
                        break
                if all_found and len(alias) > best_length:
                    best_match = inst
                    best_length = len(alias)
                    
    if best_match:
        matched_type = 'TODOS'
        if re.search(r'(?:^|[^a-z0-9])(?:1(?:st|o|a|er|º|ª)?|i)(?:[^a-z0-9]|$)', text_norm) or 'primero' in text_norm or 'primera' in text_norm:
            matched_type = '1º'
        elif re.search(r'(?:^|[^a-z0-9])(?:2(?:nd|o|a|do|º|ª)?|ii)(?:[^a-z0-9]|$)', text_norm) or 'segundo' in text_norm or 'segunda' in text_norm:
            matched_type = '2º'
        elif re.search(r'(?:^|[^a-z0-9])(?:3(?:rd|o|a|er|ro|º|ª)?|iii)(?:[^a-z0-9]|$)', text_norm) or 'tercero' in text_norm or 'tercera' in text_norm:
            matched_type = '3º'
        elif re.search(r'(?:^|[^a-z0-9])(?:4(?:th|o|a|to|º|ª)?|iv)(?:[^a-z0-9]|$)', text_norm) or 'cuarto' in text_norm or 'cuarta' in text_norm:
            matched_type = '4º'
        elif 'principal' in text_norm or 'pral' in text_norm or re.search(r'\bsolo\b', text_norm):
            matched_type = 'PRINCIPAL'
            
        return best_match['id'], best_match['name'], matched_type
        
    return None, None, None

def main():
    if len(sys.argv) < 3:
        print(json.dumps({"success": False, "message": "Faltan argumentos"}))
        return
        
    pdf_path = sys.argv[1]
    instruments_json_path = sys.argv[2]
    
    try:
        with open(instruments_json_path, 'r', encoding='utf-8') as f:
            instruments = json.load(f)
        for inst in instruments:
            inst['aliases'] = expand_aliases(inst['name'])
            
        sorted_instruments = sorted(instruments, key=lambda x: len(x['name']), reverse=True)
        
        doc = fitz.open(pdf_path)
        splits = []
        current_split = None
        
        for page_num in range(len(doc)):
            page = doc[page_num]
            rect = page.rect
            top_half = fitz.Rect(0, 0, rect.width, rect.height / 2)
            text = page.get_text("text", clip=top_half)
            
            if len(text.strip()) < 5 and TESSERACT_AVAILABLE:
                pix = page.get_pixmap(clip=top_half)
                img = Image.open(io.BytesIO(pix.tobytes()))
                try:
                    text = pytesseract.image_to_string(img)
                except:
                    pass
            
            if len(text.strip()) < 2:
                if current_split:
                    current_split['pages'].append(page_num)
                continue
                
            inst_id, inst_name, inst_type = detect_instrument(text, instruments, sorted_instruments)
            
            if inst_id:
                current_split = {
                    'instrument_id': inst_id,
                    'instrument_name': inst_name,
                    'type': inst_type,
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
                        'pages': [page_num]
                    }
                    splits.append(current_split)
                    
        output_dir = os.path.join(os.path.dirname(pdf_path), 'splits')
        if not os.path.exists(output_dir):
            os.makedirs(output_dir)
            
        results = []
        
        for idx, split in enumerate(splits):
            out_pdf = fitz.open()
            for p in split['pages']:
                out_pdf.insert_pdf(doc, from_page=p, to_page=p)
                
            out_filename = f"split_{idx}_{os.path.basename(pdf_path)}"
            out_filepath = os.path.join(output_dir, out_filename)
            out_pdf.save(out_filepath)
            out_pdf.close()
            
            results.append({
                'instrument_id': split['instrument_id'],
                'instrument_name': split['instrument_name'],
                'type': split['type'],
                'file_path': out_filepath,
                'pages': len(split['pages'])
            })
            
        doc.close()
        
        print(json.dumps({
            "success": True,
            "results": results
        }))
        
    except Exception as e:
        print(json.dumps({"success": False, "message": str(e)}))

if __name__ == "__main__":
    main()
