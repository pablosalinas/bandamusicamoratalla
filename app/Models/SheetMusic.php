<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetMusic extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'composer', 'arranger', 'pdf_file_path', 'cover_image_path', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function instruments()
    {
        return $this->belongsToMany(InstrumentCatalog::class, 'sheet_music_instruments', 'sheet_music_id', 'instrument_catalog_id')->withPivot('pdf_file_path')->withTimestamps();
    }
}
