<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['instrument_catalog_id', 'serial_number', 'brand', 'model', 'status', 'owner_type', 'user_id', 'notes'];

    public function instrument()
    {
        return $this->belongsTo(InstrumentCatalog::class, 'instrument_catalog_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
