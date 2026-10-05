<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\ExpenseHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpenseController extends Controller
{
    /**
     * Display a listing of hospital expenses with metrics and filters.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['branch', 'expenseHead', 'creator'])->latest('entry_date');

        // Search by Voucher # or Payee
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('voucher_number', 'LIKE', "%{$search}%")
                  ->orWhere('paid_to', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by Expense Head / Cost Center
        if ($request->filled('expense_head_id')) {
            $query->where('expense_head_id', $request->expense_head_id);
        }

        // Filter by Payment Mode (case-insensitive)
        if ($request->filled('payment_mode')) {
            $query->whereRaw('LOWER(payment_mode) = ?', [strtolower($request->payment_mode)]);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('entry_date', [$request->start_date, $request->end_date]);
        }

        // Compute metrics accurately using case-insensitive SQL matching
        $totalDisbursed = (clone $query)->sum('amount');

        $cashPaid = (clone $query)
            ->whereRaw('LOWER(payment_mode) = ?', ['cash'])
            ->sum('amount');

        $digitalPaid = (clone $query)
            ->whereRaw('LOWER(payment_mode) IN (?, ?, ?, ?, ?)', ['bank transfer', 'bank_transfer', 'upi', 'card', 'cheque'])
            ->sum('amount');

        $expenses = $query->paginate(15)->withQueryString();

        $branches = Branch::all();
        $expenseHeads = ExpenseHead::all();

        return view('expenses.index', compact(
            'expenses',
            'branches',
            'expenseHeads',
            'totalDisbursed',
            'cashPaid',
            'digitalPaid'
        ));
    }

    /**
     * Show the form for recording a new hospital expense.
     */
    public function create()
    {
        $branches = Branch::all();
        $expenseHeads = ExpenseHead::all();
        return view('expenses.create', compact('branches', 'expenseHeads'));
    }

    /**
     * Store a newly created expense record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_number'  => ['nullable', 'string', 'max:50'],
            'entry_date'      => ['required', 'date'],
            'branch_id'       => ['required', 'exists:branches,branch_id'],
            'expense_head_id' => ['required', 'exists:expense_heads,head_id'],
            'paid_to'         => ['required', 'string', 'max:255'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'payment_mode'    => ['required', 'string', 'max:50'],
            'description'     => ['nullable', 'string', 'max:500'],
        ]);

        if (empty($validated['voucher_number'])) {
            $validated['voucher_number'] = 'EXP-' . strtoupper(substr(uniqid(), -8));
        }

        $validated['created_by'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense voucher saved successfully.');
    }

    /**
     * Display a printable payment voucher.
     */
    public function printVoucher(Expense $expense)
    {
        $expense->load(['branch', 'expenseHead', 'creator']);
        return view('expenses.voucher', compact('expense'));
    }

    /**
     * Show the form for editing an expense entry.
     */
    public function edit(Expense $expense)
    {
        $branches = Branch::all();
        $expenseHeads = ExpenseHead::all();
        return view('expenses.edit', compact('expense', 'branches', 'expenseHeads'));
    }

    /**
     * Update an existing expense entry.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'voucher_number'  => ['nullable', 'string', 'max:50'],
            'entry_date'      => ['required', 'date'],
            'branch_id'       => ['required', 'exists:branches,branch_id'],
            'expense_head_id' => ['required', 'exists:expense_heads,head_id'],
            'paid_to'         => ['required', 'string', 'max:255'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'payment_mode'    => ['required', 'string', 'max:50'],
            'description'     => ['nullable', 'string', 'max:500'],
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense voucher updated successfully.');
    }

    /**
     * Delete an expense record.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense voucher deleted successfully.');
    }

    /**
     * Stream a CSV export of filtered expenses.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Expense::with(['branch', 'expenseHead', 'creator'])->latest('entry_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('voucher_number', 'LIKE', "%{$search}%")
                  ->orWhere('paid_to', 'LIKE', "%{$search}%");
            });
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('expense_head_id')) {
            $query->where('expense_head_id', $request->expense_head_id);
        }
        if ($request->filled('payment_mode')) {
            $query->whereRaw('LOWER(payment_mode) = ?', [strtolower($request->payment_mode)]);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('entry_date', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="expenses_ledger_' . now()->format('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () use ($expenses) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Voucher #',
                'Date',
                'Branch',
                'Cost Center',
                'Paid To',
                'Amount (INR)',
                'Payment Mode',
                'Authorized By',
                'Description'
            ]);

            foreach ($expenses as $item) {
                fputcsv($handle, [
                    $item->voucher_number ?? 'EXP-' . $item->getKey(),
                    $item->entry_date,
                    $item->branch->branch_name ?? 'Main Campus',
                    $item->expenseHead->head_name ?? 'General',
                    $item->paid_to,
                    $item->amount,
                    strtoupper($item->payment_mode),
                    $item->creator->name ?? 'Accounts Desk',
                    $item->description ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}