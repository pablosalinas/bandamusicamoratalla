const fs = require('fs');
let content = fs.readFileSync('app/Http/Controllers/Admin/SheetMusicController.php', 'utf8');

const newMethod = `    public function downloadPart(SheetMusicInstrument $sheetMusicInstrument)
    {
        if (!$sheetMusicInstrument->pdf_file_path || !\\Storage::disk('public')->exists($sheetMusicInstrument->pdf_file_path)) {
            abort(404, 'El archivo PDF no existe.');
        }

        $instrumentName = $sheetMusicInstrument->instrument->name;
        $category = $sheetMusicInstrument->category ?? 'TODOS';
        
        $safeInstName = str_replace(['/', '\\\\', ':', '*', '?', '"', '<', '>', '|'], '-', $instrumentName);
        $safeCategory = str_replace(['/', '\\\\', ':', '*', '?', '"', '<', '>', '|'], '-', $category);
        
        $originalExt = pathinfo($sheetMusicInstrument->pdf_file_path, PATHINFO_EXTENSION);
        
        $filename = "{$safeInstName} - {$safeCategory}.{$originalExt}";
        
        return response()->download(\\Storage::disk('public')->path($sheetMusicInstrument->pdf_file_path), $filename);
    }

    public function downloadAll(SheetMusic $sheetMusic)
    {
        $safeTitle = str_replace(['/', '\\\\', ':', '*', '?', '"', '<', '>', '|'], '_', $sheetMusic->title);
        $zipFileName = 'Partitura_' . $safeTitle . '.zip';
        $tempDir = storage_path('app/temp');
        
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        
        $zipPath = $tempDir . '/' . $zipFileName;

        $zip = new \\ZipArchive();
        if ($zip->open($zipPath, \\ZipArchive::CREATE | \\ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo generar el archivo ZIP.');
        }

        if ($sheetMusic->general_score_path && \\Storage::disk('public')->exists($sheetMusic->general_score_path)) {
            $path = \\Storage::disk('public')->path($sheetMusic->general_score_path);
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $zip->addFile($path, 'Guion.' . $ext);
        }

        $sheetMusic->load('instruments.instrument');
        foreach ($sheetMusic->instruments as $inst) {
            if ($inst->pdf_file_path && \\Storage::disk('public')->exists($inst->pdf_file_path)) {
                $path = \\Storage::disk('public')->path($inst->pdf_file_path);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                
                $safeInstName = str_replace(['/', '\\\\', ':', '*', '?', '"', '<', '>', '|'], '-', $inst->instrument->name);
                $category = $inst->category ?? 'TODOS';
                $safeCategory = str_replace(['/', '\\\\', ':', '*', '?', '"', '<', '>', '|'], '-', $category);
                $filename = "Particellas/{$safeInstName} - {$safeCategory}.{$ext}";
                
                $zip->addFile($path, $filename);
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }`;

// Replace downloadPart entirely to append both methods
content = content.replace(/public function downloadPart.*?}(?=\s*\n?\s*})/s, newMethod);
fs.writeFileSync('app/Http/Controllers/Admin/SheetMusicController.php', content);
