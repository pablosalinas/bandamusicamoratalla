<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_catalog_id', 'serial_number', 'model', 'status', 'user_id', 'notes',
        'instrument_brand_id', 'is_active', 'tipo_partitura', 'propiedad'
    ];

    public function instrument()
    {
        return $this->belongsTo(InstrumentCatalog::class, 'instrument_catalog_id');
    }

    public function brand()
    {
        return $this->belongsTo(InstrumentBrand::class, 'instrument_brand_id');
    }

    public function currentUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function photos()
    {
        return $this->hasMany(InstrumentPhoto::class, 'inventory_id');
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class, 'inventory_id')->orderBy('created_at', 'desc');
    }
}
