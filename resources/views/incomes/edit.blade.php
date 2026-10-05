@extends('layouts.app')

@section('content')
<div style="max-width: 680px; margin: 0 auto; padding-top: 1rem;">
    <div style="background: #ffffff; padding: 2rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0;">Edit Receipt Entry</h2>
                <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">Update patient billing information or payment record.</p>
            </div>
            <a href="{{ route('incomes.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.4rem 0.85rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600;">
                &larr; Back to Ledger
            </a>
        </div>

        @if($errors->any())
            <div style="background: #fef2f2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; margin-bottom: 1.25rem; border: 1px solid #fecdd3;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('incomes.update', $income) }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Receipt / Voucher #</label>
                    <input type="text" name="voucher_number" value="{{ old('voucher_number', $income->voucher_number) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Entry Date</label>
                    <input type="date" name="entry_date" value="{{ old('entry_date', \Carbon\Carbon::parse($income->entry_date)->format('Y-m-d')) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Hospital Branch</label>
                    <select name="branch_id" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $income->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Income Category / Head</label>
                    <select name="income_head_id" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                        @foreach($incomeHeads as $head)
                            <option value="{{ $head->id }}" {{ old('income_head_id', $income->income_head_id) == $head->id ? 'selected' : '' }}>
                                {{ $head->head_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Patient Name / Received From</label>
                    <input type="text" name="received_from" value="{{ old('received_from', $income->received_from ?? $income->payer_name) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $income->amount) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem; font-weight: 700;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Payment Mode</label>
                <select name="payment_mode" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                    @foreach(['Cash', 'UPI', 'Card', 'Bank Transfer', 'Cheque'] as $mode)
                        <option value="{{ $mode }}" {{ old('payment_mode', $income->payment_mode) == $mode ? 'selected' : '' }}>
                            {{ $mode }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Remarks / Details (Optional)</label>
                <textarea name="description" rows="2" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">{{ old('description', $income->description) }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('incomes.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    Cancel
                </a>
                <button type="submit" style="background: #2563eb; color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; cursor: pointer;">
                    Update Receipt
                </button>
            </div>
        </form>
    </div>
</div>
@endsection