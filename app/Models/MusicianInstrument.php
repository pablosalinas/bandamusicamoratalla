<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MusicianInstrument extends Pivot
{
    use HasFactory;
    
    protected $table = 'musician_instruments';

    public function photos()
    {
        return $this->hasMany(InstrumentPhoto::class, 'musician_instrument_id');
    }
}
