@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">

    <!-- Header & Campus Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Financial Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time consolidated income, expenditures, and branch collections.</p>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
            <select name="branch_id" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <option value="">All Campuses / Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->branch_id ?? $branch->id }}" {{ (string)$selectedBranchId === (string)($branch->branch_id ?? $branch->id) ? 'selected' : '' }}>
                        {{ $branch->branch_name ?? $branch->name }}
                    </option>
                @endforeach
            </select>
            @if($selectedBranchId)
                <a href="{{ route('dashboard') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium underline">Clear</a>
            @endif
        </form>
    </div>

    <!-- 3 Key Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Inflow -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Income</p>
            <p class="text-3xl font-extrabold text-emerald-600 mt-2">₹{{ number_format($totalIncome, 2) }}</p>
            <span class="inline-block mt-3 text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-medium">Inflows recorded</span>
        </div>

        <!-- Total Outflow -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Expenses</p>
            <p class="text-3xl font-extrabold text-rose-600 mt-2">₹{{ number_format($totalExpense, 2) }}</p>
            <span class="inline-block mt-3 text-xs text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full font-medium">Outflows disbursed</span>
        </div>

        <!-- Net Surplus / Balance -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Net Operating Balance</p>
            <p class="text-3xl font-extrabold {{ $netBalance >= 0 ? 'text-indigo-600' : 'text-rose-600' }} mt-2">
                ₹{{ number_format($netBalance, 2) }}
            </p>
            <span class="inline-block mt-3 text-xs text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full font-medium">Current cash surplus</span>
        </div>
    </div>

    <!-- Chart & Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Visual Analytics Chart (Fixed Box Size) -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm lg:col-span-1 flex flex-col justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-800">Collections by Category</h2>
                <p class="text-xs text-slate-500 mb-4">Distribution across hospital cost centers.</p>
            </div>
            
            <div class="relative w-full flex items-center justify-center" style="height: 240px; max-width: 100%;">
                <canvas id="incomeDonutChart"></canvas>
            </div>
        </div>

        <!-- Recent Income Ledger Snippet -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-800">Recent Inflow Transactions</h2>
                <a href="{{ route('incomes.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All Incomes &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-2">Receipt #</th>
                            <th class="py-2">Cost Center</th>
                            <th class="py-2">Staff</th>
                            <th class="py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentIncomes as $inc)
                            <tr>
                                <td class="py-2.5 font-medium text-slate-800">
                                    {{ $inc->receipt_number ?? '#REC-'.($inc->income_id ?? $inc->id) }}
                                </td>
                                <td class="py-2.5 text-slate-600">
                                    {{ $inc->costCenter->name ?? $inc->incomeHead->name ?? 'General' }}
                                </td>
                                <td class="py-2.5 text-slate-500 text-xs">
                                    {{ $inc->creator->name ?? 'System' }}
                                </td>
                                <td class="py-2.5 text-right font-bold text-emerald-600">
                                    +₹{{ number_format($inc->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-400 text-xs">No recent income transactions recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js CDN & Config -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('incomeDonutChart');
        if (!ctx) return;

        const labels = @json($chartLabels);
        const data = @json($chartValues);

        if (labels.length === 0 || data.length === 0) {
            ctx.parentElement.innerHTML = '<p class="text-slate-400 text-xs text-center py-12">No collection data to visualize yet.</p>';
            return;
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#4f46e5', // Indigo
                        '#06b6d4', // Cyan
                        '#10b981', // Emerald
                        '#f59e0b', // Amber
                        '#ec4899', // Pink
                        '#8b5cf6'  // Purple
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            padding: 10,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection