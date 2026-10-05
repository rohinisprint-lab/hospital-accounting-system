<div>
    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">HOSPITAL BRANCH</label>
    <select name="branch_id" style="border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; background: #ffffff;">
        <option value="">All Branches & Wings</option>
        @foreach(\App\Models\Branch::all() as $b)
            <option value="{{ $b->branch_id }}" {{ request('branch_id') == $b->branch_id ? 'selected' : '' }}>
                {{ $b->branch_name }}
            </option>
        @endforeach
    </select>
</div>