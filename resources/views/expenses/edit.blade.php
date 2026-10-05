<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment Voucher #{{ $expense->voucher_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Expense Voucher</h1>
                <p class="text-sm text-gray-500 mt-1">Update disbursement details for {{ $expense->voucher_number }}</p>
            </div>
            <a href="{{ route('expenses.index') }}" class="px-3 py-2 border text-sm text-gray-600 rounded hover:bg-gray-50">
                &larr; Back to Ledger
            </a>
        </div>

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('expenses.update', $expense->expense_id ?? $expense->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Voucher Number (Read-only) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Voucher Number</label>
                    <input type="text" name="voucher_number" value="{{ $expense->voucher_number }}" readonly class="w-full text-sm border border-gray-200 rounded p-2.5 bg-gray-100 text-gray-700 font-mono font-bold">
                </div>

                <!-- Disbursement Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Disbursement Date *</label>
                    <input type="date" name="entry_date" value="{{ old('entry_date', $expense->entry_date) }}" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-rose-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Branch -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Branch *</label>
                    <select name="branch_id" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-rose-500">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id ?? $branch->id }}" {{ old('branch_id', $expense->branch_id) == ($branch->branch_id ?? $branch->id) ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cost Center -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cost Center / Head *</label>
                    <select name="expense_head_id" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-rose-500">
                        @foreach($expenseHeads as $head)
                            <option value="{{ $head->head_id ?? $head->id }}" {{ old('expense_head_id', $expense->expense_head_id) == ($head->head_id ?? $head->id) ? 'selected' : '' }}>
                                {{ $head->head_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Amount -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Amount ($) *</label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $expense->amount) }}" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 font-bold focus:outline-rose-500">
                </div>

                <!-- Payment Mode -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Disbursement Mode *</label>
                    <select name="payment_mode" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-rose-500">
                        @foreach(['Cash', 'Bank Transfer', 'UPI', 'Card', 'Cheque'] as $mode)
                            <option value="{{ $mode }}" {{ old('payment_mode', $expense->payment_mode) == $mode ? 'selected' : '' }}>
                                {{ $mode }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Paid To -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Paid To / Vendor / Recipient</label>
                <input type="text" name="paid_to" value="{{ old('paid_to', $expense->paid_to) }}" class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-rose-500">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Description / Notes</label>
                <textarea name="description" rows="3" class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-rose-500">{{ old('description', $expense->description) }}</textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2.5 border text-sm text-gray-600 rounded hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded hover:bg-rose-700 shadow-sm transition">
                    Update Voucher
                </button>
            </div>
        </form>

    </div>
</body>
</html>