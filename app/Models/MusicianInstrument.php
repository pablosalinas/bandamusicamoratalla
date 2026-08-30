<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MusicianInstrument extends Pivot
{
    use HasFactory;
    
    protected $table = 'musician_instruments';

    protected $fillable = [
        'instrument_id',
        'user_id',
        'is_active',
        'tipo_partitura',
        'propiedad',
        'instrument_brand_id',
        'modelo'
    ];

    public function brand()
    {
        return $this->belongsTo(InstrumentBrand::class, 'instrument_brand_id');
    }

    public function photos()
    {
        return $this->hasMany(InstrumentPhoto::class, 'musician_instrument_id');
    }
}
