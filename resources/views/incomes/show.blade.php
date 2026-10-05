<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $income->invoice_number }}</title>
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
            .receipt-card {
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
            <a href="{{ route('incomes.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                &larr; Back to Income Ledger
            </a>
            <button onclick="window.print()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                Print Receipt
            </button>
        </div>

        <!-- Printable Receipt Card -->
        <div class="receipt-card bg-white p-8 rounded-xl shadow-sm border border-gray-100 space-y-6">
            <div class="text-center border-b pb-4">
                <h1 class="text-2xl font-black uppercase tracking-wider text-gray-900">Hospital Management System</h1>
                <p class="text-xs text-gray-500 font-medium">{{ $income->branch->branch_name ?? 'Main City Hospital' }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-[11px] font-bold uppercase tracking-wider">
                    Official Money Receipt
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 uppercase font-semibold block">Receipt / Invoice #</span>
                    <span class="font-mono font-bold text-gray-900 text-sm">{{ $income->invoice_number }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-400 uppercase font-semibold block">Receipt Date</span>
                    <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($income->entry_date)->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-semibold block">Received From (Patient / Payer)</span>
                    <span class="font-bold text-gray-800">{{ $income->received_from ?: 'Self / Walk-in Patient' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-gray-400 uppercase font-semibold block">Collection Mode</span>
                    <span class="font-bold text-gray-800">{{ $income->payment_mode }}</span>
                </div>
            </div>

            <table class="w-full text-left text-xs border-t border-b border-gray-100">
                <thead class="bg-gray-50 uppercase text-gray-400 font-bold">
                    <tr>
                        <th class="py-2.5 px-3">Revenue Department / Account</th>
                        <th class="py-2.5 px-3 text-right">Amount Collected</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-3 px-3 font-semibold text-gray-800">
                            {{ $income->incomeHead->head_name ?? 'Hospital Fee' }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-gray-900 text-sm">
                            ${{ number_format($income->amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-between items-center pt-2">
                <span class="text-xs font-bold uppercase text-gray-500">Total Amount Paid</span>
                <span class="text-2xl font-black text-emerald-600">${{ number_format($income->amount, 2) }}</span>
            </div>

            <!-- Signatures -->
            <div class="grid grid-cols-2 gap-8 pt-10 text-center text-[11px] text-gray-500">
                <div>
                    <div class="border-t border-gray-300 pt-1">Patient / Depositor Sign</div>
                </div>
                <div>
                    <div class="border-t border-gray-300 pt-1">Authorized Cashier Stamp</div>
                </div>
            </div>

            <div class="text-center text-[10px] text-gray-400 pt-2">
                Thank you for choosing our hospital. Computer-generated official receipt.
            </div>
        </div>

    </div>
</body>
</html>