<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsActivity extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'event_date', 'is_published', 'active_from', 'active_to'];

    protected $casts = [
        'event_date' => 'datetime',
        'is_published' => 'boolean',
        'active_from' => 'date',
        'active_to' => 'date',
    ];

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
