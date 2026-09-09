<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetMusic extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'composer', 'arranger', 'work_type', 'pdf_file_path', 'cover_image_path', 'is_active', 'leave_reason'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function instruments()
    {
        return $this->belongsToMany(InstrumentCatalog::class, 'sheet_music_instruments', 'sheet_music_id', 'instrument_catalog_id')->withPivot('pdf_file_path', 'tipo_partitura')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($sheetMusic) {
            // Eliminar archivos físicos de las partituras (instrumentos)
            $pivots = \App\Models\SheetMusicInstrument::where('sheet_music_id', $sheetMusic->id)->get();
            foreach ($pivots as $pivot) {
                if ($pivot->pdf_file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($pivot->pdf_file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('local')->delete($pivot->pdf_file_path);
                }
            }

            // Eliminar el archivo físico del guión
            if ($sheetMusic->pdf_file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($sheetMusic->pdf_file_path)) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($sheetMusic->pdf_file_path);
            }
        });
    }
}
