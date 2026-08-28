<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'event_date', 'type', 'is_active'];

    protected $casts = [
        'event_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getColorAttribute()
    {
        return match (strtolower($this->type)) {
            'contratada' => 'green',
            'convenio' => 'blue',
            'propias', 'propia' => 'amber',
            'ensayo' => 'gray',
            'salida' => 'purple',
            default => 'indigo',
        };
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
