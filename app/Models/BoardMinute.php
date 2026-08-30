<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMinute extends Model
{
    use HasFactory;

    protected $fillable = ['board_id', 'date', 'title', 'content', 'signed_pdf_path'];

    protected $casts = [
        'date' => 'date',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
