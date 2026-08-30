<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['musician_instrument_id', 'photo_path'];

    public function musicianInstrument()
    {
        return $this->belongsTo(MusicianInstrument::class, 'musician_instrument_id');
    }
}
