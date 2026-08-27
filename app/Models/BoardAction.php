<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardAction extends Model
{
    use HasFactory;

    protected $fillable = ['board_id', 'title', 'description', 'document_path', 'action_date'];

    protected $casts = [
        'action_date' => 'date',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
