<!-- Sub-Navigation & Date Preset Bar -->
<div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6">
    <!-- Section Links -->
    <nav class="flex items-center space-x-2">
        <a href="{{ route('incomes.index') }}" 
           class="px-3.5 py-1.5 text-sm font-semibold rounded-md transition {{ request()->routeIs('incomes.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Incomes
        </a>
        <a href="{{ route('expenses.index') }}" 
           class="px-3.5 py-1.5 text-sm font-semibold rounded-md transition {{ request()->routeIs('expenses.*') ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Expenses
        </a>
        <a href="{{ route('reports.financial') }}" 
           class="px-3.5 py-1.5 text-sm font-semibold rounded-md transition {{ request()->routeIs('reports.financial*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            Financial Statement
        </a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('income-heads.index') }}" 
           class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-md">
            Income Heads
        </a>
        <a href="{{ route('expense-heads.index') }}" 
           class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-md">
            Cost Centers
        </a>
    </nav>

    <!-- Quick Date Range Presets -->
    <div class="flex items-center space-x-1 text-xs font-medium text-gray-600">
        <span class="text-gray-400 mr-1">Period:</span>
        @php
            $currentUrl = request()->url();
            $params = request()->except(['from_date', 'to_date', 'page']);
        @endphp
        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($params, ['from_date' => now()->toDateString(), 'to_date' => now()->toDateString()])) }}" 
           class="px-2.5 py-1 rounded border border-gray-200 bg-gray-50 hover:bg-gray-100">Today</a>
        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($params, ['from_date' => now()->startOfWeek()->toDateString(), 'to_date' => now()->endOfWeek()->toDateString()])) }}" 
           class="px-2.5 py-1 rounded border border-gray-200 bg-gray-50 hover:bg-gray-100">This Week</a>
        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($params, ['from_date' => now()->startOfMonth()->toDateString(), 'to_date' => now()->endOfMonth()->toDateString()])) }}" 
           class="px-2.5 py-1 rounded border border-gray-200 bg-gray-50 hover:bg-gray-100">This Month</a>
        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($params, ['from_date' => now()->startOfYear()->toDateString(), 'to_date' => now()->endOfYear()->toDateString()])) }}" 
           class="px-2.5 py-1 rounded border border-gray-200 bg-gray-50 hover:bg-gray-100">This Year</a>
        <a href="{{ $currentUrl }}" class="px-2 py-1 text-rose-600 hover:underline">Reset</a>
    </div>
</div>