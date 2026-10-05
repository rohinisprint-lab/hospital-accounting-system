<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\CashClosing;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\Income;
use App\Models\IncomeHead;
use Illuminate\Database\Seeder;

class HospitalDataSeeder extends Seeder
{
    public function run(): void
    {
        // Branches
        $b1 = Branch::create([
            'branch_code' => 'MCH-01',
            'branch_name' => 'Main City Hospital',
        ]);
        $b2 = Branch::create([
            'branch_code' => 'NWC-02',
            'branch_name' => 'North Wing Clinic',
        ]);

        // Income Heads
        $hLab = IncomeHead::create(['head_name' => 'Laboratory & Diagnostics']);
        $hOpd = IncomeHead::create(['head_name' => 'OPD Consultation']);
        $hPhm = IncomeHead::create(['head_name' => 'Pharmacy Sales']);

        // Expense Heads
        $hMed = ExpenseHead::create(['head_name' => 'Medical Supplies']);
        $hDoc = ExpenseHead::create(['head_name' => 'Doctor & Staff Honararium']);

        // Income Entries (with voucher_number)
        Income::create([
            'voucher_number' => 'REC-2026-001',
            'branch_id'      => $b1->branch_id ?? $b1->id,
            'income_head_id' => $hLab->head_id ?? $hLab->id,
            'entry_date'     => now()->toDateString(),
            'amount'         => 450.00,
            'payment_mode'   => 'Bank Transfer',
            'received_from'  => 'Patient John'
        ]);
        Income::create([
            'voucher_number' => 'REC-2026-002',
            'branch_id'      => $b1->branch_id ?? $b1->id,
            'income_head_id' => $hOpd->head_id ?? $hOpd->id,
            'entry_date'     => now()->toDateString(),
            'amount'         => 240.00,
            'payment_mode'   => 'Cash',
            'received_from'  => 'Walk-in Patient'
        ]);
        Income::create([
            'voucher_number' => 'REC-2026-003',
            'branch_id'      => $b1->branch_id ?? $b1->id,
            'income_head_id' => $hPhm->head_id ?? $hPhm->id,
            'entry_date'     => now()->toDateString(),
            'amount'         => 670.00,
            'payment_mode'   => 'Card',
            'received_from'  => 'Pharmacy Counter'
        ]);
        Income::create([
            'voucher_number' => 'REC-2026-004',
            'branch_id'      => $b1->branch_id ?? $b1->id,
            'income_head_id' => $hOpd->head_id ?? $hOpd->id,
            'entry_date'     => now()->toDateString(),
            'amount'         => 120.00,
            'payment_mode'   => 'Card',
            'received_from'  => 'Smoothie'
        ]);

        // Expense Entries
        Expense::create([
            'voucher_number'  => 'EXP-2026-001',
            'branch_id'       => $b1->branch_id ?? $b1->id,
            'expense_head_id' => $hMed->head_id ?? $hMed->id,
            'entry_date'      => now()->toDateString(),
            'amount'          => 1699.00,
            'payment_mode'    => 'Bank Transfer',
            'paid_to'         => 'Surgical Supplies Corp'
        ]);
        Expense::create([
            'voucher_number'  => 'EXP-2026-002',
            'branch_id'       => $b1->branch_id ?? $b1->id,
            'expense_head_id' => $hDoc->head_id ?? $hDoc->id,
            'entry_date'      => now()->toDateString(),
            'amount'          => 450.00,
            'payment_mode'    => 'Bank Transfer',
            'paid_to'         => 'Dr. Adams'
        ]);

        // Cash Drawer Audit Record
        CashClosing::create([
            'branch_id'             => $b1->branch_id ?? $b1->id,
            'closing_date'          => now()->toDateString(),
            'closed_by'             => 'dwarfy',
            'opening_float'         => 100.00,
            'system_cash_in'        => 240.00,
            'system_cash_out'       => 0.00,
            'expected_cash'         => 340.00,
            'counted_physical_cash' => 340.00,
            'discrepancy'           => 0.00,
            'notes'                 => 'Evening audit balanced',
        ]);
    }
}