<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Income Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Record Patient / Income Receipt</h1>
                <p class="text-sm text-gray-500 mt-1">Generate an audited receipt for hospital fees, pharmacy, or diagnostic services</p>
            </div>
            <a href="{{ route('incomes.index') }}" class="px-3 py-2 border text-sm text-gray-600 rounded hover:bg-gray-50">
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

        <form action="{{ route('incomes.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Receipt Number (Auto-Generated) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Receipt / Invoice #</label>
                    <input type="text" name="invoice_number" value="INC-{{ strtoupper(substr(uniqid(), -8)) }}" readonly class="w-full text-sm border border-gray-200 rounded p-2.5 bg-gray-100 text-gray-700 font-mono font-bold">
                </div>

                <!-- Receipt Date -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Receipt Date *</label>
                    <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Hospital Branch -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Branch *</label>
                    <select name="branch_id" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-emerald-500">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id ?? $branch->id }}" {{ old('branch_id') == ($branch->branch_id ?? $branch->id) ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Revenue Category -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Revenue Channel / Head *</label>
                    <select name="income_head_id" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-emerald-500">
                        <option value="">Select Category</option>
                        @foreach($incomeHeads as $head)
                            <option value="{{ $head->head_id ?? $head->id }}" {{ old('income_head_id') == ($head->head_id ?? $head->id) ? 'selected' : '' }}>
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
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" placeholder="0.00" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 font-bold focus:outline-emerald-500">
                </div>

                <!-- Payment Mode -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1"> 
                        Collection Mode *
                    </label>
                    <select name="payment_mode" required class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-emerald-500">
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="UPI">UPI</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
            </div>

            <!-- Received From -->
            <div>
    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Received From (Patient / Payer)</label>
    <input type="text" name="received_from" value="{{ old('received_from') }}" placeholder="e.g. John Doe / Patient File #1024" class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-emerald-500">
</div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Description / Patient Notes</label>
                <textarea name="description" rows="3" placeholder="Consultation notes, prescription ID, or billing details..." class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800 focus:outline-emerald-500">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                <a href="{{ route('incomes.index') }}" class="px-4 py-2.5 border text-sm text-gray-600 rounded hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded hover:bg-emerald-700 shadow-sm transition">
                    Save Receipt & Print Slip
                </button>
            </div>
        </form>

    </div>
</body>
</html>