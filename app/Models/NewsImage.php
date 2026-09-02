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
        return request()->getSchemeAndHttpHost() . '/uploads/news/' . $this->file_path;
    }
}
