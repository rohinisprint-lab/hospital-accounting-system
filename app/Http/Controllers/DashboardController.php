<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\Income;
use App\Models\IncomeHead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Total Metrics
        $totalIncome = Income::sum('amount');
        $totalExpense = Expense::sum('amount');
        $netSurplus = $totalIncome - $totalExpense;

        // 2. Today's Metrics
        $today = Carbon::today()->toDateString();
        $todayIncome = Income::whereDate('entry_date', $today)->sum('amount');
        $todayExpense = Expense::whereDate('entry_date', $today)->sum('amount');

        // 3. Category Breakdown (Top Cost Centers & Incomes)
        $topIncomeHeads = IncomeHead::withSum('incomes', 'amount')
            ->orderByDesc('incomes_sum_amount')
            ->take(4)
            ->get();

        $topExpenseHeads = ExpenseHead::withSum('expenses', 'amount')
            ->orderByDesc('expenses_sum_amount')
            ->take(4)
            ->get();

       // 4. Recent Activities (with audit creator)
$recentIncomes = Income::with(['incomeHead', 'creator'])
    ->latest('entry_date')
    ->take(5)
    ->get();

$recentExpenses = Expense::with(['expenseHead', 'creator'])
    ->latest('entry_date')
    ->take(5)
    ->get();

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'netSurplus',
            'todayIncome',
            'todayExpense',
            'topIncomeHeads',
            'topExpenseHeads',
            'recentIncomes',
            'recentExpenses'
        ));
    }
}