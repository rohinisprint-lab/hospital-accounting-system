@extends('layouts.app')

@section('content')
<div style="max-width: 550px; margin: 0 auto; padding-top: 1rem;">
    <div style="background: #ffffff; padding: 2rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0;">Edit Branch Details</h2>
                <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">Update location, contact info, or branch name.</p>
            </div>
            <a href="{{ route('branches.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.4rem 0.85rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600;">
                &larr; Back
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

        <form method="POST" action="{{ route('branches.update', $branch) }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            @method('PUT')

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Branch Code</label>
                <input type="text" name="branch_code" value="{{ old('branch_code', $branch->branch_code) }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Branch Name</label>
                <input type="text" name="branch_name" value="{{ old('branch_name', $branch->branch_name) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Location / Address</label>
                <input type="text" name="location" value="{{ old('location', $branch->location) }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Contact Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('branches.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    Cancel
                </a>
                <button type="submit" style="background: #4f46e5; color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; cursor: pointer;">
                    Update Branch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection