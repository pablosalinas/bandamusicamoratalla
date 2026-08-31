<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'fiscal_year_id',
        'date',
        'type',
        'description',
        'amount',
        'document_path',
        'is_reconciled',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_reconciled' => 'boolean',
    ];

    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? asset('storage/' . $this->document_path) : null;
    }
}
