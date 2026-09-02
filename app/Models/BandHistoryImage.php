<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandHistoryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'description',
        'sort_order',
    ];

    public function getUrlAttribute()
    {
        // Usamos url absoluta para compatibilidad y consistencia
        return request()->getSchemeAndHttpHost() . '/uploads/band-history/' . $this->file_path;
    }
}
