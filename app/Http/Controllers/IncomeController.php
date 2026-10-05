<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Income;
use App\Models\IncomeHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IncomeController extends Controller
{
    /**
     * Display a listing of patient receipts and incomes with filters.
     */
    public function index(Request $request)
    {
        $query = Income::with(['branch', 'incomeHead', 'creator'])->latest('entry_date');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('income_head_id')) {
            $query->where('income_head_id', $request->income_head_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('entry_date', [$request->start_date, $request->end_date]);
        }

        $incomes = $query->paginate(15)->withQueryString();

        // Metric aggregates
        $totalCollected = (clone $query)->sum('amount');
        $todayCollected = Income::whereDate('entry_date', Carbon::today())->sum('amount');
        $thisMonthCollected = Income::whereMonth('entry_date', Carbon::now()->month)
            ->whereYear('entry_date', Carbon::now()->year)
            ->sum('amount');

        $branches = Branch::all();
        $incomeHeads = IncomeHead::all();

        return view('incomes.index', compact(
            'incomes',
            'branches',
            'incomeHeads',
            'totalCollected',
            'todayCollected',
            'thisMonthCollected'
        ));
    }

    /**
     * Show the form for creating a new patient receipt.
     */
    public function create()
    {
        $branches = Branch::all();
        $incomeHeads = IncomeHead::all();
        return view('incomes.create', compact('branches', 'incomeHeads'));
    }

    /**
     * Store a newly created receipt entry in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'branch_id'      => ['required', 'exists:branches,branch_id'],
        'income_head_id' => ['required', 'exists:income_heads,head_id'],
        'received_from'  => ['required', 'string', 'max:255'],
        'amount'         => ['required', 'numeric', 'min:0.01'],
        'entry_date'     => ['required', 'date'],
        'payment_mode'   => ['required', 'string', 'max:50'], // Allows Cash, Card, Bank Transfer, UPI, Cheque
        'description'    => ['nullable', 'string', 'max:500'],
    ]);

    $validated['created_by'] = auth()->id();
    $validated['voucher_number'] = $request->input('invoice_number') 
        ?? ('INC-' . strtoupper(substr(uniqid(), -8)));

    $income = Income::create($validated);

    // Redirect straight to the printable receipt slip
    return redirect()->route('incomes.receipt', $income)
        ->with('success', 'Patient receipt recorded successfully.');
}

    /**
     * Display a clean, printable hospital receipt for a patient payment.
     */
    public function printReceipt(Income $income)
    {
        $income->load(['branch', 'incomeHead', 'creator']);
        return view('incomes.receipt', compact('income'));
    }

    /**
     * Show the form for editing an existing receipt.
     */
    public function edit(Income $income)
    {
        $branches = Branch::all();
        $incomeHeads = IncomeHead::all();
        return view('incomes.edit', compact('income', 'branches', 'incomeHeads'));
    }

    /**
     * Update the specified receipt in storage.
     */
    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'branch_id'      => ['required', 'exists:branches,branch_id'],
            'income_head_id' => ['required', 'exists:income_heads,head_id'],
            'received_from'  => ['required', 'string', 'max:255'],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'entry_date'     => ['required', 'date'],
            'payment_mode' => ['required', 'string', 'max:50'],
            'description'    => ['nullable', 'string', 'max:500'],
        ]);

        $income->update($validated);

        return redirect()->route('incomes.index')->with('success', 'Receipt details updated successfully.');
    }

    /**
     * Remove the specified receipt from storage.
     */
    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Receipt record deleted successfully.');
    }

    /**
     * Stream a CSV export of filtered income receipts.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Income::with(['branch', 'incomeHead', 'creator'])->latest('entry_date');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('income_head_id')) {
            $query->where('income_head_id', $request->income_head_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('entry_date', [$request->start_date, $request->end_date]);
        }

        $incomes = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="incomes_ledger_' . now()->format('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () use ($incomes) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Receipt #',
                'Date',
                'Branch',
                'Income Category',
                'Received From',
                'Payment Mode',
                'Billed By',
                'Amount (INR)',
                'Description'
            ]);

            foreach ($incomes as $item) {
                fputcsv($handle, [
                    $item->voucher_number ?? 'REC-' . $item->getKey(),
                    $item->entry_date,
                    $item->branch->branch_name ?? 'N/A',
                    $item->incomeHead->head_name ?? 'General',
                    $item->received_from ?? $item->payer_name ?? 'Patient',
                    strtoupper($item->payment_mode ?? 'CASH'),
                    $item->creator->name ?? 'Admin',
                    $item->amount,
                    $item->description ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}