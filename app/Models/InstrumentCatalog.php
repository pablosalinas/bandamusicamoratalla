<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentCatalog extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'instrument_section_id', 'order_index', 'description', 'is_active', 'leave_reason'];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public function section()
    {
        return $this->belongsTo(InstrumentSection::class, 'instrument_section_id');
    }

    public function musicians()
    {
        return $this->belongsToMany(User::class, 'musician_instruments', 'instrument_catalog_id', 'user_id')->withTimestamps();
    }
    
    public function sheetMusic()
    {
        return $this->belongsToMany(SheetMusic::class, 'sheet_music_instruments', 'instrument_catalog_id', 'sheet_music_id')->withPivot('pdf_file_path')->withTimestamps();
    }
}
