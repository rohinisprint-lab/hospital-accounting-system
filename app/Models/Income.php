<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $table = 'incomes';

    // Tell Eloquent that your primary key column is income_id
    protected $primaryKey = 'income_id';

    protected $fillable = [
        'voucher_number',
        'invoice_number',
        'branch_id',
        'income_head_id',
        'received_from',
        'payer_name',
        'amount',
        'entry_date',
        'payment_mode',
        'description',
        'created_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function incomeHead()
    {
        return $this->belongsTo(IncomeHead::class, 'income_head_id', 'head_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}