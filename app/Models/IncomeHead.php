<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeHead extends Model
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

    public function incomes()
    {
        return $this->hasMany(Income::class, 'income_head_id', 'head_id');
    }
}