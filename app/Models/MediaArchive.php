<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'type',
        'composer',
        'music_type',
        'performance_date',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'performance_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function images()
    {
        return $this->hasMany(MediaArchiveImage::class)->orderBy('sort_order');
    }
}
