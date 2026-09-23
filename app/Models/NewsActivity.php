<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsActivity extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'event_date', 'is_published', 'show_in_hemeroteca', 'active_from', 'active_to', 'event_id'];

    protected $casts = [
        'event_date' => 'datetime',
        'is_published' => 'boolean',
        'show_in_hemeroteca' => 'boolean',
        'active_from' => 'date',
        'active_to' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getLinkedEventAttribute(): ?Event
    {
        // 1. Si tiene event_id asignado, buscar ese evento
        try {
            if (!empty($this->event_id)) {
                $event = Event::find($this->event_id);
                if ($event) return $event;
            }
        } catch (\Throwable $e) {}

        // 2. Detección automática por coincidencia exacta de título
        try {
            $event = Event::where('name', $this->title)->first();
            if ($event && \Illuminate\Support\Facades\Schema::hasColumn('news_activities', 'event_id')) {
                // Auto-vincular para futuras consultas
                $this->updateQuietly(['event_id' => $event->id]);
            }
            return $event;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function isLinkedToEvent(): bool
    {
        return $this->linked_event !== null;
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function newsImages()
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(NewsImage::class)->orderBy('sort_order');
    }
}
