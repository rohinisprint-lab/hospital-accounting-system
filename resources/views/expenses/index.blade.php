@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Top Action Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Expense Management</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Track operational expenditures, vendor disbursements, and branch expenses.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="{{ route('expenses.export', request()->query()) }}" style="background-color: #ffffff; color: #374151; border: 1px solid #d1d5db; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 700;">
                📥 Export CSV
            </a>
            <a href="{{ route('expenses.create') }}" style="background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700;">
                + Record Expense
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; border: 1px solid #a7f3d0;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Metrics Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
        <div style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Total Disbursed</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 0.25rem;">₹{{ number_format($totalDisbursed ?? 0, 2) }}</div>
        </div>
        <div style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Cash Paid Out</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 0.25rem;">₹{{ number_format($cashPaid ?? 0, 2) }}</div>
        </div>
        <div style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Bank / Digital Paid</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 0.25rem;">₹{{ number_format($digitalPaid ?? 0, 2) }}</div>
        </div>
        <div style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Active Vouchers</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 0.25rem;">{{ $expenses->total() }}</div>
        </div>
    </div>

    <!-- Filters Section -->
    <form method="GET" action="{{ route('expenses.index') }}" style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; display: flex; flex-direction: column; gap: 1rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Search Payee / Voucher</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Voucher # or payee..." style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Branch</label>
                <select name="branch_id" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box;">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->branch_id }}" {{ request('branch_id') == $branch->branch_id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Cost Center / Head</label>
                <select name="expense_head_id" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box;">
                    <option value="">All Categories</option>
                    @foreach($expenseHeads as $head)
                        <option value="{{ $head->head_id ?? $head->id }}" {{ request('expense_head_id') == ($head->head_id ?? $head->id) ? 'selected' : '' }}>
                            {{ $head->head_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Payment Mode</label>
                <select name="payment_mode" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box;">
                    <option value="">All Modes</option>
                    <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="upi" {{ request('payment_mode') == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="bank_transfer" {{ request('payment_mode') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cheque" {{ request('payment_mode') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 0.5rem; justify-content: flex-start; align-items: center;">
            <button type="submit" style="background: #1f2937; color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 700; cursor: pointer;">
                Filter
            </button>
            <a href="{{ route('expenses.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600;">
                Reset
            </a>
        </div>
    </form>

    <!-- Clean Expense Table (No stray top action buttons) -->
    <div style="background: #ffffff; border-radius: 0.5rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <div style="padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f3f4f6;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #111827;">Expense Vouchers</h3>
            <span style="font-size: 0.75rem; color: #6b7280; font-weight: 600; background: #f3f4f6; padding: 0.25rem 0.6rem; border-radius: 9999px;">
                Total Records: {{ $expenses->total() }}
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 0.75rem 1rem;">Voucher #</th>
                        <th style="padding: 0.75rem 1rem;">Date</th>
                        <th style="padding: 0.75rem 1rem;">Branch</th>
                        <th style="padding: 0.75rem 1rem;">Cost Center</th>
                        <th style="padding: 0.75rem 1rem;">Paid To</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Amount</th>
                        <th style="padding: 0.75rem 1rem; text-align: center;">Mode</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem 1rem; font-weight: 700; color: #111827;">
                                {{ $expense->voucher_number ?? 'EXP-' . $expense->getKey() }}
                            </td>
                            <td style="padding: 0.75rem 1rem; color: #4b5563;">
                                {{ \Carbon\Carbon::parse($expense->entry_date)->format('d M Y') }}
                            </td>
                            <td style="padding: 0.75rem 1rem; color: #4b5563;">
                                {{ $expense->branch->branch_name ?? 'Main Hospital Campus' }}
                            </td>
                            <td style="padding: 0.75rem 1rem; color: #4b5563;">
                                <span style="background: #f3f4f6; color: #374151; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">
                                    {{ $expense->expenseHead->head_name ?? 'General' }}
                                </span>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                                {{ $expense->paid_to }}
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 800; color: #dc2626;">
                                ₹{{ number_format($expense->amount, 2) }}
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <span style="background: #eef2ff; color: #4338ca; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase;">
                                    {{ $expense->payment_mode }}
                                </span>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center; justify-content: flex-end;">
                                    <a href="{{ route('expenses.voucher', $expense) }}" target="_blank" style="color: #dc2626; text-decoration: none; font-size: 0.75rem; font-weight: 700; background: #fef2f2; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                        Print
                                    </a>
                                    <a href="{{ route('expenses.edit', $expense) }}" style="color: #4f46e5; text-decoration: none; font-size: 0.75rem; font-weight: 700; background: #e0e7ff; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 2rem; text-align: center; color: #9ca3af;">No expense vouchers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection