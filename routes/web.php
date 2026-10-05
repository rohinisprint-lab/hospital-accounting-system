<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ExpenseHeadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Incomes / Patient Billing
    Route::get('/incomes/export', [IncomeController::class, 'exportCsv'])->name('incomes.export');
    Route::get('/incomes/{income}/receipt', [IncomeController::class, 'printReceipt'])->name('incomes.receipt');
    Route::resource('incomes', IncomeController::class);

    // 3. Expenses / Vouchers
    Route::get('/expenses/export', [ExpenseController::class, 'exportCsv'])->name('expenses.export');
    Route::get('/expenses/{expense}/voucher', [ExpenseController::class, 'printVoucher'])->name('expenses.voucher');
    Route::resource('expenses', ExpenseController::class);

    // 4. Financial Statements
    Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/financial/export', [ReportController::class, 'exportFinancial'])->name('reports.financial.export');

    // 5. Administrative Configurations (Admin Only)
    Route::middleware(['can:admin-access'])->group(function () {
    Route::resource('branches', BranchController::class);
    Route::resource('cost-centers', ExpenseHeadController::class); // <-- mapped to your existing controller
    Route::resource('staff', StaffController::class);
});

});

require __DIR__.'/auth.php';