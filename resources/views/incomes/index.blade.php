@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Top Action Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Patient Receipts & Incomes</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Track OPD collections, diagnostic bills, and patient payments.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="{{ route('incomes.export', request()->query()) }}" style="background-color: #ffffff; color: #374151; border: 1px solid #d1d5db; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 700;">
                📥 Export CSV
            </a>
            <a href="{{ route('incomes.create') }}" style="background-color: #059669; color: #ffffff !important; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700;">
                + New Receipt Entry
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
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Filtered Collections</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #059669; margin-top: 0.25rem;">₹{{ number_format($totalCollected ?? 0, 2) }}</div>
        </div>
        <div style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Today's Inflow</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-top: 0.25rem;">₹{{ number_format($todayCollected ?? 0, 2) }}</div>
        </div>
        <div style="background: #ffffff; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">This Month's Inflow</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #2563eb; margin-top: 0.25rem;">₹{{ number_format($thisMonthCollected ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- Filters Section -->
    <form method="GET" action="{{ route('incomes.index') }}" style="background: #ffffff; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end;">
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">BRANCH</label>
            <select name="branch_id" style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.4rem 0.6rem; font-size: 0.8125rem;">
                <option value="">All Branches</option>
                @foreach($branches as $b)
                    <option value="{{ $b->branch_id }}" {{ request('branch_id') == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">CATEGORY</label>
            <select name="income_head_id" style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.4rem 0.6rem; font-size: 0.8125rem;">
                <option value="">All Categories</option>
                @foreach($incomeHeads as $head)
                    <option value="{{ $head->head_id ?? $head->id }}" {{ request('income_head_id') == ($head->head_id ?? $head->id) ? 'selected' : '' }}>{{ $head->head_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">START DATE</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.4rem 0.6rem; font-size: 0.8125rem;">
        </div>

        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">END DATE</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.4rem 0.6rem; font-size: 0.8125rem;">
        </div>

        <button type="submit" style="background: #374151; color: #ffffff; border: none; padding: 0.45rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 700; cursor: pointer;">
            Filter
        </button>
        <a href="{{ route('incomes.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.45rem 0.85rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600;">
            Reset
        </a>
    </form>

    <!-- Incomes Table -->
    <div style="background: #ffffff; border-radius: 0.5rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.75rem 1rem;">Receipt #</th>
                    <th style="padding: 0.75rem 1rem;">Date</th>
                    <th style="padding: 0.75rem 1rem;">Branch</th>
                    <th style="padding: 0.75rem 1rem;">Patient / Source</th>
                    <th style="padding: 0.75rem 1rem;">Category</th>
                    <th style="padding: 0.75rem 1rem;">Billed By</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Amount</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $income)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 0.75rem 1rem; font-weight: 700; color: #111827;">
                            {{ $income->voucher_number ?? 'REC-' . $income->getKey() }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">
                            {{ \Carbon\Carbon::parse($income->entry_date)->format('d M Y') }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">
                            {{ $income->branch->branch_name ?? 'N/A' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">
                            {{ $income->received_from ?? $income->payer_name ?? 'Patient' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">
                            {{ $income->incomeHead->head_name ?? 'General' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                            {{ $income->creator->name ?? 'Front Desk' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #059669;">
                            ₹{{ number_format($income->amount, 2) }}
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem; align-items: center; justify-content: flex-end;">
                                <a href="{{ route('incomes.receipt', $income) }}" target="_blank" style="color: #2563eb; text-decoration: none; font-size: 0.75rem; font-weight: 700; background: #eff6ff; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                    Print
                                </a>
                                <a href="{{ route('incomes.edit', $income) }}" style="color: #4f46e5; text-decoration: none; font-size: 0.75rem; font-weight: 700;">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 2rem; text-align: center; color: #9ca3af;">No patient receipts recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($incomes->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $incomes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection