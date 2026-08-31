<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'is_closed'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
    ];

    public function movements()
    {
        return $this->hasMany(BudgetMovement::class);
    }

    public function getTotalIncomeAttribute()
    {
        return $this->movements()->where('type', 'income')->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        return $this->movements()->where('type', 'expense')->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->total_income - $this->total_expense;
    }
}
