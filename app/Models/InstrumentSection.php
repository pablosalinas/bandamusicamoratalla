<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'order_index',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Cuerda superior (si esta es una subcuerda).
     */
    public function parent()
    {
        return $this->belongsTo(InstrumentSection::class, 'parent_id');
    }

    /**
     * Subcuerdas pertenecientes a esta cuerda.
     */
    public function children()
    {
        return $this->hasMany(InstrumentSection::class, 'parent_id')->orderBy('order_index')->orderBy('name');
    }

    /**
     * Instrumentos asociados a esta sección/cuerda.
     */
    public function instruments()
    {
        return $this->hasMany(InstrumentCatalog::class, 'instrument_section_id')->orderBy('order_index')->orderBy('name');
    }

    /**
     * Músicos asociados directamente a esta sección/cuerda.
     */
    public function musicians()
    {
        return $this->hasMany(User::class, 'instrument_section_id');
    }

    /**
     * Obtener el nombre completo con jerarquía (ej. Viento Madera > Clarinetes).
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return "{$this->parent->name} > {$this->name}";
        }
        return $this->name;
    }
}
