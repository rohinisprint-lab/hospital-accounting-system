<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseHead extends Model
{
    protected $primaryKey = 'head_id';

    protected $fillable = [
        'head_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function expenses()
    {
        // 2nd argument: foreign key on 'expenses' table ('expense_head_id')
        // 3rd argument: local key on 'expense_heads' table ('head_id')
        return $this->hasMany(Expense::class, 'expense_head_id', 'head_id');
    }
}