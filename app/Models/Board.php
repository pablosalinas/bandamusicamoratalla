<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = ['start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function members()
    {
        return $this->hasMany(BoardMember::class);
    }

    public function actions()
    {
        return $this->hasMany(BoardAction::class);
    }

    public function minutes()
    {
        return $this->hasMany(BoardMinute::class);
    }
}
