<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_activity_id',
        'file_path',
        'description',
        'sort_order',
    ];

    public function newsActivity()
    {
        return $this->belongsTo(NewsActivity::class);
    }

    public function getUrlAttribute()
    {
        $path = ltrim($this->file_path, '/');
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'uploads/news/')) {
            return request()->getSchemeAndHttpHost() . '/' . $path;
        }
        return request()->getSchemeAndHttpHost() . '/uploads/news/' . $path;
    }
}
