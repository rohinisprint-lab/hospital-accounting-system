@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding-top: 1rem;">
    <div style="background: #ffffff; padding: 2rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0;">Add Staff Member</h2>
                <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">Provision staff credentials and module permissions.</p>
            </div>
            <a href="{{ route('users.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.4rem 0.85rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600;">
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

        <form method="POST" action="{{ route('users.store') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Dr. Rajesh Kumar / Priya Desk" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Email Address (Login ID)</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="staff@hospital.com" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Staff Role & Module Access</label>
                <select name="role" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                    <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>Receptionist (Patient Billing & Incomes Only)</option>
                    <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant (Cost Centers & Operational Expenses)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Unrestricted Access & Financial Audits)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Password</label>
                    <input type="password" name="password" required placeholder="Minimum 8 characters" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat password" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('users.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    Cancel
                </a>
                <button type="submit" style="background: #4f46e5; color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; cursor: pointer;">
                    Create Staff Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection