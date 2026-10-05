@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <!-- Page Header & CSV Export -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Hospital Financial Statement</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Executive revenue, operational disbursements, and audit trails.</p>
        </div>
        <div>
            <a href="{{ route('reports.financial.export', request()->query()) }}" style="background-color: #047857; color: #ffffff !important; text-decoration: none; padding: 0.6rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                📥 Export CSV Audit File
            </a>
        </div>
    </div>

    <!-- Filter Form Bar -->
    <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route('reports.financial') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                
                <!-- From Date -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">From Date</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? request('start_date', now()->startOfMonth()->toDateString()) }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box;">
                </div>

                <!-- To Date -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">To Date</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? request('end_date', now()->endOfMonth()->toDateString()) }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box;">
                </div>

                <!-- Branch Dropdown -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Branch</label>
                    <select name="branch_id" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; box-sizing: border-box; background: #ffffff;">
                        <option value="">All Hospital Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ (string) request('branch_id') === (string) $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <button type="submit" style="background-color: #4f46e5; color: #ffffff; border: none; padding: 0.55rem 1.5rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; cursor: pointer;">
                    Generate Report
                </button>
                <a href="{{ route('reports.financial') }}" style="background-color: #f3f4f6; color: #374151; text-decoration: none; padding: 0.55rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Executive Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;">
        
        <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.025em;">Total Period Income</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: #059669; margin-top: 0.5rem;">
                ₹{{ number_format($totalIncome ?? 0, 2) }}
            </div>
        </div>

        <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.025em;">Total Period Expenses</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: #dc2626; margin-top: 0.5rem;">
                ₹{{ number_format($totalExpenses ?? 0, 2) }}
            </div>
        </div>

        <div style="background: #ffffff; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.025em;">Period Net Cash Flow</span>
            <div style="font-size: 1.75rem; font-weight: 800; color: {{ ($netMargin ?? 0) >= 0 ? '#2563eb' : '#dc2626' }}; margin-top: 0.5rem;">
                ₹{{ number_format($netMargin ?? 0, 2) }}
            </div>
        </div>

    </div>

    <!-- Breakdown Tables Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
        
        <!-- Income Head Breakdown -->
        <div style="background: #ffffff; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #111827;">Income Collections by Head</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 0.75rem 1rem;">Category / Head</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomeByHead as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem 1rem; color: #374151; font-weight: 600;">
                                {{ $item->incomeHead->head_name ?? 'Uncategorized' }}
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #059669;">
                                ₹{{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="padding: 1.5rem; text-align: center; color: #9ca3af;">No income collections in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Expense Head Breakdown -->
        <div style="background: #ffffff; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #111827;">Disbursements by Cost Center</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 0.75rem; text-transform: uppercase;">
                        <th style="padding: 0.75rem 1rem;">Cost Center</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenseByHead as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem 1rem; color: #374151; font-weight: 600;">
                                {{ $item->expenseHead->head_name ?? 'General Operational' }}
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: #dc2626;">
                                ₹{{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="padding: 1.5rem; text-align: center; color: #9ca3af;">No expenses recorded in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection