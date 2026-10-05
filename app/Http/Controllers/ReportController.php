<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\Income;
use App\Models\IncomeHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display the consolidated or branch-wise financial statement.
     */
    public function financial(Request $request)
    {
        // 1. Resolve date range filters (default to current month)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $branchId  = $request->input('branch_id');

        // 2. Base Query for Incomes
        $incomeQuery = Income::whereBetween('entry_date', [$startDate, $endDate]);
        if (!empty($branchId)) {
            $incomeQuery->where('branch_id', $branchId);
        }

        // 3. Base Query for Expenses
        $expenseQuery = Expense::whereBetween('entry_date', [$startDate, $endDate]);
        if (!empty($branchId)) {
            $expenseQuery->where('branch_id', $branchId);
        }

        // 4. Calculate Aggregate Totals
        $totalIncome   = (clone $incomeQuery)->sum('amount');
        $totalExpenses = (clone $expenseQuery)->sum('amount');
        $netMargin     = $totalIncome - $totalExpenses;

        // 5. Incomes Breakdown by Category / Head
        $incomeByHead = (clone $incomeQuery)
            ->selectRaw('income_head_id, SUM(amount) as total')
            ->groupBy('income_head_id')
            ->with('incomeHead')
            ->get();

        // 6. Expenses Breakdown by Cost Center / Head
        $expenseByHead = (clone $expenseQuery)
            ->selectRaw('expense_head_id, SUM(amount) as total')
            ->groupBy('expense_head_id')
            ->with('expenseHead')
            ->get();

        // 7. All Branches for the Dropdown
        $branches = Branch::all();

        return view('reports.financial', compact(
            'startDate',
            'endDate',
            'branchId',
            'branches',
            'totalIncome',
            'totalExpenses',
            'netMargin',
            'incomeByHead',
            'expenseByHead'
        ));
    }

    /**
     * Stream CSV export matching current financial statement filters.
     */
    public function exportFinancial(Request $request): StreamedResponse
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $branchId  = $request->input('branch_id');

        $incomeQuery = Income::with(['branch', 'incomeHead'])->whereBetween('entry_date', [$startDate, $endDate]);
        $expenseQuery = Expense::with(['branch', 'expenseHead'])->whereBetween('entry_date', [$startDate, $endDate]);

        if (!empty($branchId)) {
            $incomeQuery->where('branch_id', $branchId);
            $expenseQuery->where('branch_id', $branchId);
        }

        $incomes  = $incomeQuery->get();
        $expenses = $expenseQuery->get();

        $filename = 'financial_audit_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($incomes, $expenses) {
            $handle = fopen('php://output', 'w');

            // Header Row
            fputcsv($handle, ['Type', 'Voucher / Ref #', 'Date', 'Branch', 'Head / Category', 'Party / Source', 'Mode', 'Amount (INR)']);

            // Income Entries
            foreach ($incomes as $item) {
                fputcsv($handle, [
                    'INCOME',
                    $item->voucher_number ?? 'REC-' . $item->getKey(),
                    $item->entry_date,
                    $item->branch->branch_name ?? 'N/A',
                    $item->incomeHead->head_name ?? 'General Income',
                    $item->received_from ?? $item->payer_name ?? 'Patient',
                    strtoupper($item->payment_mode ?? 'CASH'),
                    $item->amount,
                ]);
            }

            // Expense Entries
            foreach ($expenses as $item) {
                fputcsv($handle, [
                    'EXPENSE',
                    $item->voucher_number ?? 'EXP-' . $item->getKey(),
                    $item->entry_date,
                    $item->branch->branch_name ?? 'N/A',
                    $item->expenseHead->head_name ?? 'General Operational',
                    $item->paid_to,
                    strtoupper($item->payment_mode ?? 'CASH'),
                    '-' . $item->amount,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}