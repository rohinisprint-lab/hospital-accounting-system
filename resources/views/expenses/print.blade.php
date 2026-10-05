<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Voucher - {{ $expense->voucher_number ?? 'EXP-' . $expense->getKey() }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
            color: #111827;
        }
        .voucher-card {
            max-width: 500px;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #e5e7eb;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }
        .hospital-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: #991b1b;
            margin: 0;
            text-transform: uppercase;
        }
        .branch-info {
            font-size: 0.8125rem;
            color: #6b7280;
            margin-top: 4px;
        }
        .title-badge {
            display: inline-block;
            margin-top: 8px;
            background: #fef2f2;
            color: #991b1b;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.875rem;
        }
        .label {
            color: #6b7280;
        }
        .value {
            font-weight: 600;
            color: #111827;
            text-align: right;
        }
        .amount-box {
            background: #fef2f2;
            border: 1px solid #fecdd3;
            border-radius: 6px;
            padding: 12px;
            margin: 16px 0;
            text-align: center;
        }
        .amount-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #dc2626;
        }
        .footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .no-print {
            text-align: center;
            margin-bottom: 16px;
        }
        .btn-print {
            background-color: #dc2626;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.875rem;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .voucher-card {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Payment Voucher</button>
        <button class="btn-print" style="background:#6b7280;" onclick="window.close()">Close Window</button>
    </div>

    <div class="voucher-card">
        <div class="header">
            <h1 class="hospital-name">Hospital Management System</h1>
            <div class="branch-info">{{ $expense->branch->branch_name ?? 'Main Branch' }}</div>
            <div class="title-badge">Payment Disbursement Voucher</div>
        </div>

        <div class="data-row">
            <span class="label">Voucher No:</span>
            <span class="value">{{ $expense->voucher_number ?? 'EXP-' . $expense->getKey() }}</span>
        </div>

        <div class="data-row">
            <span class="label">Date:</span>
            <span class="value">{{ \Carbon\Carbon::parse($expense->entry_date)->format('d-M-Y') }}</span>
        </div>

        <div class="data-row">
            <span class="label">Paid To (Beneficiary):</span>
            <span class="value">{{ $expense->paid_to }}</span>
        </div>

        <div class="data-row">
            <span class="label">Cost Center / Head:</span>
            <span class="value">{{ $expense->expenseHead->head_name ?? 'Operational' }}</span>
        </div>

        <div class="data-row">
            <span class="label">Payment Mode:</span>
            <span class="value">{{ $expense->payment_mode ?? 'Cash' }}</span>
        </div>

        @if($expense->description)
            <div class="data-row">
                <span class="label">Purpose / Notes:</span>
                <span class="value">{{ $expense->description }}</span>
            </div>
        @endif

        <div class="amount-box">
            <div style="font-size: 0.75rem; text-transform: uppercase; color: #991b1b; font-weight: 700;">Disbursed Amount</div>
            <div class="amount-value">₹{{ number_format($expense->amount, 2) }}</div>
        </div>

        <div class="footer">
            <div>
                <div><strong>Authorized By:</strong> {{ $expense->creator->name ?? 'Accounts Officer' }}</div>
                <div>{{ now()->format('d/m/Y h:i A') }}</div>
            </div>
            <div style="text-align: center; border-top: 1px dashed #9ca3af; width: 140px; padding-top: 4px;">
                Receiver's Signature
            </div>
        </div>
    </div>

</body>
</html>