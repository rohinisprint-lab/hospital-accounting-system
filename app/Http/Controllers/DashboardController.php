<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeHead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedBranchId = $request->get('branch_id');

        // Query builders with optional branch filtering
        $incomesQuery = Income::query();
        $expensesQuery = Expense::query();

        if ($selectedBranchId) {
            $incomesQuery->where('branch_id', $selectedBranchId);
            $expensesQuery->where('branch_id', $selectedBranchId);
        }

        // Summary Metric Cards
        $totalIncome = (float) $incomesQuery->sum('amount');
        $totalExpense = (float) $expensesQuery->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Recent Activity Lists using safe relationships
        $recentIncomes = (clone $incomesQuery)
            ->with(['branch'])
            ->limit(5)
            ->get();

        $recentExpenses = (clone $expensesQuery)
            ->with(['branch'])
            ->limit(5)
            ->get();

        // Income category breakdown for the chart
        $heads = IncomeHead::withSum('incomes', 'amount')->get();
        $chartLabels = $heads->pluck('name')->toArray();
        $chartValues = $heads->map(fn($h) => (float) ($h->incomes_sum_amount ?? 0))->toArray();

        // Dropdown filter options
       $branches = Branch::all();
        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'netBalance',
            'recentIncomes',
            'recentExpenses',
            'chartLabels',
            'chartValues',
            'branches',
            'selectedBranchId'
        ));
    }
}