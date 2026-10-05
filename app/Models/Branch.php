<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $primaryKey = 'branch_id';

    // Make sure 'branch_code' is present here:
    protected $fillable = [
        'branch_code',
        'branch_name',
        'location',
        'phone',
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class, 'branch_id', 'branch_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'branch_id', 'branch_id');
    }
}