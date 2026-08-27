<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['news_activity_id', 'file_path', 'type', 'sort_order'];

    public function newsActivity()
    {
        return $this->belongsTo(NewsActivity::class);
    }
}
