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

    /**
     * Obtener todas las cuerdas y subcuerdas ordenadas según la jerarquía (orden padre -> orden hijo).
     */
    public static function getOrderedForSelect($onlyActive = true)
    {
        $query = static::with(['children' => function($q) use ($onlyActive) {
            if ($onlyActive) {
                $q->where('is_active', true);
            }
            $q->orderBy('order_index')->orderBy('name');
        }])->whereNull('parent_id');

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        $parents = $query->orderBy('order_index')->orderBy('name')->get();

        $orderedList = collect();
        foreach ($parents as $parent) {
            // Añadir el padre (cuerda principal)
            $orderedList->push($parent);
            // Añadir sus hijos (subcuerdas) en su orden
            foreach ($parent->children as $child) {
                $orderedList->push($child);
            }
        }

        // Por si existieran registros huérfanos sin padre que no se contemplaron
        $orphanSubsections = static::whereNotNull('parent_id')
            ->whereNotIn('id', $orderedList->pluck('id'))
            ->when($onlyActive, fn($q) => $q->where('is_active', true))
            ->orderBy('order_index')
            ->orderBy('name')
            ->get();

        foreach ($orphanSubsections as $orphan) {
            $orderedList->push($orphan);
        }

        return $orderedList;
    }
}
