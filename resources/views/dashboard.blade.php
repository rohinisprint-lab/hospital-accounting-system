@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.75rem;">

    <!-- Top Greeting & Branch Overview -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.625rem; font-weight: 800; color: #111827; margin: 0;">Financial Dashboard</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Real-time overview of collections, expenditures, and net surplus.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            @if(in_array(Auth::user()->role ?? '', ['admin', 'receptionist']))
                <a href="{{ route('incomes.create') }}" style="background-color: #059669; color: #ffffff !important; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 700;">
                    + New Receipt
                </a>
            @endif
            @if(in_array(Auth::user()->role ?? '', ['admin', 'accountant']))
                <a href="{{ route('expenses.create') }}" style="background-color: #dc2626; color: #ffffff !important; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 700;">
                    + New Expense
                </a>
            @endif
        </div>
    </div>

    <!-- 3 Big Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
        
    @php
    // Match the exact variable names used in your metric cards:
    // (e.g. $totalIncome / $totalExpense or $totalCollections / $totalDisbursements)
    $inflow = (float) ($totalCollections ?? $totalIncome ?? 870);
    $outflow = (float) ($totalDisbursements ?? $totalExpense ?? 1220);
    $totalFlow = $inflow + $outflow;

    $inflowPct = $totalFlow > 0 ? round(($inflow / $totalFlow) * 100) : 50;
    $outflowPct = 100 - $inflowPct;
@endphp
   

<div style="background: #ffffff; padding: 1.25rem 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; margin-top: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; font-size: 0.8125rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">
        <span style="color: #059669;">Collections Ratio ({{ $inflowPct }}%)</span>
        <span style="color: #dc2626;">Disbursements Ratio ({{ $outflowPct }}%)</span>
    </div>
    <div style="height: 10px; width: 100%; background: #fee2e2; border-radius: 9999px; overflow: hidden; display: flex;">
        <div style="width: {{ $inflowPct }}%; background: #10b981; height: 100%;"></div>
        <div style="width: {{ $outflowPct }}%; background: #ef4444; height: 100%;"></div>
    </div>
</div>
        <!-- Total Incomes -->
        <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8125rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Total Collections</span>
                <span style="background: #ecfdf5; color: #059669; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Inflow</span>
            </div>
            <div style="font-size: 1.875rem; font-weight: 800; color: #059669; margin-top: 0.75rem;">
                ₹{{ number_format($totalIncome, 2) }}
            </div>
            <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem;">
                Today's Receipt: <strong style="color: #374151;">₹{{ number_format($todayIncome, 2) }}</strong>
            </div>
        </div>

        <!-- Total Expenses -->
        <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8125rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Total Disbursements</span>
                <span style="background: #fef2f2; color: #dc2626; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Outflow</span>
            </div>
            <div style="font-size: 1.875rem; font-weight: 800; color: #dc2626; margin-top: 0.75rem;">
                ₹{{ number_format($totalExpense, 2) }}
            </div>
            <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem;">
                Today's Payouts: <strong style="color: #374151;">₹{{ number_format($todayExpense, 2) }}</strong>
            </div>
        </div>

        <!-- Net Surplus -->
        <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8125rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Net Cash Position</span>
                <span style="background: {{ $netSurplus >= 0 ? '#eff6ff' : '#fff1f2' }}; color: {{ $netSurplus >= 0 ? '#2563eb' : '#e11d48' }}; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                    {{ $netSurplus >= 0 ? 'Surplus' : 'Deficit' }}
                </span>
            </div>
            <div style="font-size: 1.875rem; font-weight: 800; color: {{ $netSurplus >= 0 ? '#111827' : '#e11d48' }}; margin-top: 0.75rem;">
                ₹{{ number_format($netSurplus, 2) }}
            </div>
            <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem;">
                Net Balance across all cost centers
            </div>
        </div>

    </div>

    <!-- Recent Feeds Grid: Recent Receipts vs Recent Expenses -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;">
        
        <!-- Recent Receipts -->
        <div style="background: #ffffff; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1rem; font-weight: 800; color: #111827; margin: 0;">Recent Receipts</h3>
                <a href="{{ route('incomes.index') }}" style="font-size: 0.75rem; font-weight: 700; color: #2563eb; text-decoration: none;">View All &rarr;</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentIncomes as $rec)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #f3f4f6;">
                        <div>
                            <div style="font-weight: 700; font-size: 0.875rem; color: #111827;">{{ $rec->received_from ?? $rec->payer_name ?? 'Walk-in Patient' }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">
                                {{ $rec->incomeHead->head_name ?? 'Receipt' }} &bull; Billed by: {{ $rec->creator->name ?? 'Admin' }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 800; font-size: 0.875rem; color: #059669;">+₹{{ number_format($rec->amount, 2) }}</div>
                            <div style="font-size: 0.6875rem; color: #9ca3af;">{{ \Carbon\Carbon::parse($rec->entry_date)->format('d M') }}</div>
                        </div>
                    </div>
                @empty
                    <div style="font-size: 0.8125rem; color: #9ca3af; text-align: center; padding: 1rem 0;">No receipts recorded yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Expenses -->
        <div style="background: #ffffff; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1rem; font-weight: 800; color: #111827; margin: 0;">Recent Expenses</h3>
                <a href="{{ route('expenses.index') }}" style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-decoration: none;">View All &rarr;</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentExpenses as $exp)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.5rem; border-bottom: 1px solid #f3f4f6;">
                        <div>
                            <div style="font-weight: 700; font-size: 0.875rem; color: #111827;">{{ $exp->paid_to }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">
                                {{ $exp->expenseHead->head_name ?? 'Expense' }} &bull; Auth by: {{ $exp->creator->name ?? 'Admin' }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 800; font-size: 0.875rem; color: #dc2626;">-₹{{ number_format($exp->amount, 2) }}</div>
                            <div style="font-size: 0.6875rem; color: #9ca3af;">{{ \Carbon\Carbon::parse($exp->entry_date)->format('d M') }}</div>
                        </div>
                    </div>
                @empty
                    <div style="font-size: 0.8125rem; color: #9ca3af; text-align: center; padding: 1rem 0;">No expense entries recorded yet.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection