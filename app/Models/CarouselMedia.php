<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselMedia extends Model
{
    use HasFactory;
    
    protected $fillable = ['file_path', 'type', 'sort_order'];
}
