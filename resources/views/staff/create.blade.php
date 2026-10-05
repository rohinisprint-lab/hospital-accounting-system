@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 1.25rem;">Register New Staff Account</h2>

    <form method="POST" action="{{ route('staff.store') }}">
        @csrf

        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">FULL NAME *</label>
            <input type="text" name="name" required value="{{ old('name') }}" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.625rem; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">EMAIL ADDRESS *</label>
            <input type="email" name="email" required value="{{ old('email') }}" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.625rem; box-sizing: border-box;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">ROLE *</label>
                <select name="role" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.625rem; box-sizing: border-box;">
                    <option value="staff">Staff (Front Desk)</option>
                    <option value="accountant">Accountant</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">ASSIGNED BRANCH</label>
                <select name="branch_id" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.625rem; box-sizing: border-box;">
                    <option value="">All Campuses</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">PASSWORD *</label>
                <input type="password" name="password" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.625rem; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">CONFIRM PASSWORD *</label>
                <input type="password" name="password_confirmation" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.625rem; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="{{ route('staff.index') }}" style="padding: 0.625rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; text-decoration: none; color: #475569; font-weight: 600;">Cancel</a>
            <button type="submit" style="background: #4f46e5; color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 0.375rem; font-weight: 700; cursor: pointer;">Save Staff</button>
        </div>
    </form>
</div>
@endsection