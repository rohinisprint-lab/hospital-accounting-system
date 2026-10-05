<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $primaryKey = 'expense_id'; // or 'id', matching your schema

    protected $fillable = [
    'voucher_number',
    'entry_date',
    'branch_id',
    'created_by',
    'expense_head_id',
    'paid_to',
    'amount',
    'payment_mode',
    'description',
];

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function expenseHead()
    {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_id', 'head_id');
    }
}