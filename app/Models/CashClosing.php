<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashClosing extends Model
{
    protected $primaryKey = 'closing_id';

    protected $fillable = [
        'branch_id',
        'closing_date',
        'opening_float',
        'system_cash_in',
        'system_cash_out',
        'expected_cash',
        'counted_physical_cash',
        'discrepancy',
        'closed_by',
        'notes',
    ];
}