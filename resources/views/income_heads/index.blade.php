<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Categories & Heads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Income Categories & Heads</h1>
                <p class="text-sm text-gray-500 mt-1">Manage departmental revenue channels and fee structures</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('incomes.index') }}" class="px-3 py-2 border text-sm text-gray-600 rounded hover:bg-gray-50">&larr; Income Ledger</a>
                <a href="{{ route('expense-heads.index') }}" class="px-3 py-2 border text-sm text-rose-600 rounded hover:bg-rose-50">Expense Categories &rarr;</a>
            </div>
        </div>

        @if(view()->exists('partials.financial-nav'))
            @include('partials.financial-nav')
        @endif

        <!-- Session Notifications -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Add Income Category Form -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 h-fit space-y-4">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Add Revenue Category</h2>
                
                <form action="{{ route('income-heads.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Head Name *</label>
                        <input type="text" name="head_name" required placeholder="e.g. Pharmacy Sales, ICU Charges" class="w-full text-sm border border-gray-300 rounded p-2 text-gray-800 focus:outline-emerald-500">
                        @error('head_name')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Description / Notes</label>
                        <textarea name="description" rows="3" placeholder="Optional notes for this revenue account..." class="w-full text-sm border border-gray-300 rounded p-2 text-gray-800 focus:outline-emerald-500"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2 bg-emerald-600 text-white text-sm font-semibold rounded hover:bg-emerald-700 shadow-sm transition">
                        Create Income Head
                    </button>
                </form>
            </div>

            <!-- Existing Income Heads Table -->
            <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Existing Income Channels</h2>
                    <span class="text-xs text-gray-500">Total: {{ $heads->total() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b">
                            <tr>
                                <th class="py-3 px-4">Head Name</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Receipts Linked</th>
                                <th class="py-3 px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($heads as $head)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $head->head_name }}</div>
                                        @if($head->description)
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $head->description }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($head->is_active ?? true)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-gray-200 text-gray-600 uppercase">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $head->incomes_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $head->incomes_count }} receipts
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <form action="{{ route('income-heads.toggle', $head->head_id ?? $head->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs font-medium {{ ($head->is_active ?? true) ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}">
                                                    {{ ($head->is_active ?? true) ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>

                                            @if($head->incomes_count == 0)
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('income-heads.destroy', $head->head_id ?? $head->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this revenue category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-900 font-medium">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 text-xs">
                                        No income categories found. Create one using the form.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                @if($heads->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $heads->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</body>
</html>