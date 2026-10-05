<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Slip - {{ $income->voucher_number ?? 'REC-' . $income->getKey() }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
            color: #111827;
        }
        .receipt-card {
            max-width: 480px;
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
            color: #1e3a8a;
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
            background: #ecfdf5;
            color: #065f46;
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
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin: 16px 0;
            text-align: center;
        }
        .amount-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #059669;
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
            background-color: #2563eb;
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
            .receipt-card {
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
        <button class="btn-print" onclick="window.print()">Print Receipt</button>
        <button class="btn-print" style="background:#6b7280;" onclick="window.close()">Close Window</button>
    </div>

    <div class="receipt-card">
        <div class="header">
            <h1 class="hospital-name">Hospital Management System</h1>
            <div class="branch-info">{{ $income->branch->branch_name ?? 'Main Branch' }}</div>
            <div class="title-badge">Official Patient Billing Receipt</div>
        </div>

        <div class="data-row">
            <span class="label">Receipt No:</span>
            <span class="value">{{ $income->voucher_number ?? 'REC-' . $income->getKey() }}</span>
        </div>

        <div class="data-row">
            <span class="label">Date:</span>
            <span class="value">{{ \Carbon\Carbon::parse($income->entry_date)->format('d-M-Y') }}</span>
        </div>

        <div class="data-row">
            <span class="label">Patient / Received From:</span>
            <span class="value">{{ $income->received_from ?? $income->payer_name ?? 'General Patient' }}</span>
        </div>

        <div class="data-row">
            <span class="label">Income Head / Category:</span>
            <span class="value">{{ $income->incomeHead->head_name ?? 'General Income' }}</span>
        </div>

        <div class="data-row">
            <span class="label">Payment Mode:</span>
            <span class="value">{{ $income->payment_mode ?? 'Cash' }}</span>
        </div>

        @if($income->description)
            <div class="data-row">
                <span class="label">Remarks:</span>
                <span class="value">{{ $income->description }}</span>
            </div>
        @endif

        <div class="amount-box">
            <div style="font-size: 0.75rem; text-transform: uppercase; color: #6b7280; font-weight: 700;">Total Amount Received</div>
            <div class="amount-value">₹{{ number_format($income->amount, 2) }}</div>
        </div>

        <div class="footer">
            <div>
                <div><strong>Billed By:</strong> {{ $income->creator->name ?? 'Admin Staff' }}</div>
                <div>{{ now()->format('d/m/Y h:i A') }}</div>
            </div>
            <div style="text-align: center; border-top: 1px dashed #9ca3af; width: 140px; padding-top: 4px;">
                Authorized Seal / Sign
            </div>
        </div>
    </div>

</body>
</html>