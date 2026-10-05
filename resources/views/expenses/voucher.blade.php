<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Voucher #{{ $expense->voucher_number ?? 'EXP-' . $expense->id }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 2rem; color: #1f2937; background: #f9fafb; }
        .voucher-card { max-width: 650px; margin: 0 auto; background: #fff; padding: 2.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #ef4444; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; font-size: 0.875rem; }
        .amount-box { background: #fef2f2; border: 1px solid #fecdd3; border-radius: 0.375rem; padding: 1.25rem; text-align: center; margin: 1.5rem 0; }
        .btn-print { background: #dc2626; color: #fff; border: none; padding: 0.625rem 1.25rem; border-radius: 0.375rem; font-weight: 700; cursor: pointer; }
        .signatures { margin-top: 3rem; display: flex; justify-content: space-between; font-size: 0.8125rem; color: #6b7280; border-top: 1px solid #f3f4f6; padding-top: 1.25rem; }
        @media print {
            body { padding: 0; background: #fff; }
            .voucher-card { border: none; box-shadow: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="voucher-card">
    <div class="no-print" style="text-align: right; margin-bottom: 1rem;">
        <button class="btn-print" onclick="window.print()">🖨️ Print Voucher</button>
    </div>

    <div class="header">
        <div>
            <h2 style="margin: 0; color: #991b1b; font-size: 1.5rem;">{{ $expense->branch->branch_name ?? 'Hospital Management' }}</h2>
            <p style="margin: 0.25rem 0 0; font-size: 0.8125rem; color: #6b7280;">{{ $expense->branch->location ?? 'Hospital Medical Campus' }}</p>
        </div>
        <div style="text-align: right;">
            <div style="font-weight: 800; font-size: 1.125rem; color: #374151;">PAYMENT VOUCHER</div>
            <div style="font-size: 0.8125rem; color: #6b7280;">#{{ $expense->voucher_number ?? 'EXP-' . $expense->id }}</div>
        </div>
    </div>

    <div class="meta-grid">
        <div>
            <p style="margin: 0.25rem 0;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($expense->entry_date)->format('d M Y') }}</p>
            <p style="margin: 0.25rem 0;"><strong>Paid To (Vendor/Staff):</strong> {{ $expense->paid_to }}</p>
            <p style="margin: 0.25rem 0;"><strong>Cost Center:</strong> {{ $expense->expenseHead->head_name ?? 'Operational Expense' }}</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0.25rem 0;"><strong>Payment Mode:</strong> <span style="text-transform: uppercase;">{{ $expense->payment_mode }}</span></p>
            <p style="margin: 0.25rem 0;"><strong>Authorized By:</strong> {{ $expense->creator->name ?? 'Accounts Officer' }}</p>
        </div>
    </div>

    <div class="amount-box">
        <span style="font-size: 0.875rem; color: #991b1b; font-weight: 600; text-transform: uppercase;">Total Disbursed</span>
        <h1 style="margin: 0.25rem 0 0; font-size: 2.25rem; color: #7f1d1d;">₹{{ number_format($expense->amount, 2) }}</h1>
    </div>

    @if($expense->description)
        <p style="font-size: 0.875rem; color: #4b5563; margin-top: 1rem;">
            <strong>Expense Particulars / Purpose:</strong> {{ $expense->description }}
        </p>
    @endif

    <div class="signatures">
        <div>
            <p style="margin: 0 0 2rem 0;">Prepared By:</p>
            <strong>{{ $expense->creator->name ?? 'Finance Desk' }}</strong>
        </div>
        <div>
            <p style="margin: 0 0 2rem 0;">Receiver's Signature:</p>
            <strong>__________________________</strong>
        </div>
        <div>
            <p style="margin: 0 0 2rem 0;">Audit Approval:</p>
            <strong>__________________________</strong>
        </div>
    </div>
</div>

</body>
</html>