<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_id', 'photo_path', 'description'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
