<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaArchiveImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_archive_id',
        'file_path',
        'sort_order'
    ];

    public function mediaArchive()
    {
        return $this->belongsTo(MediaArchive::class);
    }
}
