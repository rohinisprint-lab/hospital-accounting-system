<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher #{{ $expense->voucher_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print, nav, button, a {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .voucher-card {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Top Actions (Hidden in Print) -->
        <div class="flex justify-between items-center no-print">
            <a href="{{ route('expenses.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                &larr; Back to Expense Ledger
            </a>
            <button onclick="window.print()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                Print Voucher
            </button>
        </div>

        <!-- Printable Voucher Card -->
        <div class="voucher-card bg-white p-8 rounded-xl shadow-sm border border-gray-100 space-y-6">
            <div class="text-center border-b pb-4">
                <h1 class="text-2xl font-black uppercase tracking-wider text-gray-900">Hospital Management System</h1>
                <p class="text-xs text-gray-500 font-medium">{{ $expense->branch->branch_name ?? 'Main City Hospital' }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-full text-[11px] font-bold uppercase tracking-wider">
                    Official Payment / Disbursement Voucher
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 uppercase font-semibold block">Voucher #</span>
                    <span class="font-mono font-bold text-gray-900 text-sm">{{ $expense->voucher_number }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-400 uppercase font-semibold block">Disbursement Date</span>
                    <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($expense->entry_date)->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-semibold block">Paid To (Beneficiary / Vendor)</span>
                    <span class="font-bold text-gray-800">{{ $expense->paid_to ?: '—' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-400 uppercase font-semibold block">Disbursement Mode</span>
                    <span class="font-bold text-gray-800">{{ $expense->payment_mode }}</span>
                </div>
            </div>

            <table class="w-full text-left text-xs border-t border-b border-gray-100">
                <thead class="bg-gray-50 uppercase text-gray-400 font-bold">
                    <tr>
                        <th class="py-2.5 px-3">Cost Center / Head</th>
                        <th class="py-2.5 px-3 text-right">Amount Disbursed</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-3 px-3 font-semibold text-gray-800">
                            {{ $expense->expenseHead->head_name ?? 'Operational Expense' }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-gray-900 text-sm">
                            ${{ number_format($expense->amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-between items-center pt-2">
                <span class="text-xs font-bold uppercase text-gray-500">Total Amount Disbursed</span>
                <span class="text-2xl font-black text-rose-600">${{ number_format($expense->amount, 2) }}</span>
            </div>

            <!-- Signature Footers -->
            <div class="grid grid-cols-3 gap-6 pt-10 text-center text-[11px] text-gray-500">
                <div>
                    <div class="border-t border-gray-300 pt-1">Prepared By</div>
                </div>
                <div>
                    <div class="border-t border-gray-300 pt-1">Verified By</div>
                </div>
                <div>
                    <div class="border-t border-gray-300 pt-1">Receiver's Sign</div>
                </div>
            </div>

            <div class="text-center text-[10px] text-gray-400 pt-2">
                Authorized hospital accounting voucher. Retain signed copy for audit trail.
            </div>
        </div>

    </div>
</body>
</html>