<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SheetMusicInstrument extends Pivot
{
    protected $table = 'sheet_music_instruments';
    
    protected $fillable = [
        'sheet_music_id',
        'instrument_catalog_id',
        'tipo_partitura',
        'pdf_file_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($sheetMusicInstrument) {
            if ($sheetMusicInstrument->pdf_file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($sheetMusicInstrument->pdf_file_path)) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($sheetMusicInstrument->pdf_file_path);
            }
        });
    }
}
