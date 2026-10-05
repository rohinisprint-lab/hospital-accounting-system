<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Cash Closing</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">End-of-Shift Cash Balancing</h1>
                <p class="text-sm text-gray-500 mt-1">Reconcile physical register cash against today's ledger transactions</p>
            </div>
            <a href="{{ route('reports.financial') }}" class="px-3 py-2 border text-sm text-gray-600 rounded hover:bg-gray-50">
                &larr; Financial Statement
            </a>
        </div>

        @if(view()->exists('partials.financial-nav'))
            @include('partials.financial-nav')
        @endif

        <!-- Shift Balancing Form -->
        <form method="POST" action="{{ route('reports.cash-closing.store') }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 space-y-6">
            @csrf

            <!-- Shift Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Audit Date</label>
                    <input type="date" name="closing_date" value="{{ $today ?? date('Y-m-d') }}" required class="w-full text-sm border border-gray-300 rounded p-2.5 bg-gray-50 text-gray-800">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Branch</label>
                    <select name="branch_id" class="w-full text-sm border border-gray-300 rounded p-2.5 bg-white text-gray-800">
                        <option value="">All Branches / Main Register</option>
                        @foreach($branches ?? [] as $branch)
                            @php $bId = $branch->branch_id ?? $branch->id; @endphp
                            <option value="{{ $bId }}" {{ ($branchId ?? '') == $bId ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Calculated Ledger Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xs font-semibold text-gray-500 uppercase">System Cash In</span>
                    <div class="text-xl font-bold text-emerald-600 mt-1">${{ number_format($systemCashIn ?? 0, 2) }}</div>
                    <input type="hidden" id="systemCashIn" name="system_cash_in" value="{{ $systemCashIn ?? 0 }}">
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xs font-semibold text-gray-500 uppercase">System Cash Out</span>
                    <div class="text-xl font-bold text-rose-600 mt-1">${{ number_format($systemCashOut ?? 0, 2) }}</div>
                    <input type="hidden" id="systemCashOut" name="system_cash_out" value="{{ $systemCashOut ?? 0 }}">
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xs font-semibold text-gray-500 uppercase">Net Day Drawer Flow</span>
                    <div class="text-xl font-bold text-gray-800 mt-1">${{ number_format($netExpected ?? 0, 2) }}</div>
                </div>
            </div>

            <!-- Physical Count Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Opening Cash Float ($)</label>
                    <input type="number" step="0.01" min="0" id="openingFloat" name="opening_float" value="0.00" required class="w-full text-sm border border-gray-300 rounded p-2.5 focus:outline-indigo-500 text-gray-800">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Physical Counted Cash ($)</label>
                    <input type="number" step="0.01" id="countedPhysicalCash" name="counted_physical_cash" placeholder="0.00" required class="w-full text-sm border border-gray-300 rounded p-2.5 focus:outline-indigo-500 text-gray-800 font-bold">
                </div>
            </div>

            <!-- Dynamic Live Balance Banner -->
            <div class="p-4 rounded-lg bg-indigo-50 border border-indigo-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs font-bold text-indigo-900 uppercase tracking-wide">Expected Cash Total</span>
                    <div id="expectedDisplay" class="text-2xl font-black text-indigo-700 mt-0.5">$0.00</div>
                    <p class="text-xs text-indigo-600 mt-0.5">(Opening Float + Cash In - Cash Out)</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Shift Variance</span>
                    <div id="discrepancyDisplay" class="text-2xl font-black text-gray-700 mt-0.5">$0.00</div>
                    <span id="varianceBadge" class="inline-block text-[11px] font-bold px-2 py-0.5 rounded bg-gray-200 text-gray-700 uppercase mt-0.5">Balanced</span>
                </div>
            </div>

            <!-- Sign-Off Block -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cashier / Staff Name</label>
                    <input type="text" name="closed_by" required placeholder="e.g. dwarfy" class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Notes / Discrepancy Reason</label>
                    <input type="text" name="notes" placeholder="Optional audit notes..." class="w-full text-sm border border-gray-300 rounded p-2.5 text-gray-800">
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('reports.financial') }}" class="px-4 py-2.5 border text-sm font-semibold text-gray-600 rounded hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded hover:bg-indigo-700 shadow-sm">
                    Submit & Freeze Shift Closing
                </button>
            </div>
        </form>

    </div>

    <!-- Live Variance Calculator -->
    <script>
        const systemIn = parseFloat(document.getElementById('systemCashIn').value) || 0;
        const systemOut = parseFloat(document.getElementById('systemCashOut').value) || 0;
        const floatInput = document.getElementById('openingFloat');
        const countedInput = document.getElementById('countedPhysicalCash');
        const expectedDisplay = document.getElementById('expectedDisplay');
        const discrepancyDisplay = document.getElementById('discrepancyDisplay');
        const varianceBadge = document.getElementById('varianceBadge');

        function recalculate() {
            const openFloat = parseFloat(floatInput.value) || 0;
            const counted = parseFloat(countedInput.value) || 0;
            const expected = openFloat + systemIn - systemOut;
            const diff = counted - expected;

            expectedDisplay.textContent = '$' + expected.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            discrepancyDisplay.textContent = (diff >= 0 ? '+' : '') + '$' + diff.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (diff === 0) {
                varianceBadge.textContent = 'Balanced';
                varianceBadge.className = 'inline-block text-[11px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 uppercase mt-0.5';
                discrepancyDisplay.className = 'text-2xl font-black text-emerald-700 mt-0.5';
            } else if (diff > 0) {
                varianceBadge.textContent = 'Cash Overage';
                varianceBadge.className = 'inline-block text-[11px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800 uppercase mt-0.5';
                discrepancyDisplay.className = 'text-2xl font-black text-blue-700 mt-0.5';
            } else {
                varianceBadge.textContent = 'Cash Shortage';
                varianceBadge.className = 'inline-block text-[11px] font-bold px-2 py-0.5 rounded bg-rose-100 text-rose-800 uppercase mt-0.5';
                discrepancyDisplay.className = 'text-2xl font-black text-rose-700 mt-0.5';
            }
        }

        floatInput.addEventListener('input', recalculate);
        countedInput.addEventListener('input', recalculate);
        recalculate();
    </script>
</body>
</html>