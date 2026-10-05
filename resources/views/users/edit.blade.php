@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding-top: 1rem;">
    <div style="background: #ffffff; padding: 2rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #111827; margin: 0;">Edit Staff Member</h2>
                <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">Update name, role permissions, or reset access password.</p>
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

        <form method="POST" action="{{ route('users.update', $user) }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            @method('PUT')

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Official Email (Login ID)</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; text-transform: uppercase;">Assigned Role & Permissions</label>
                <select name="role" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                    <option value="receptionist" {{ old('role', $user->role) === 'receptionist' ? 'selected' : '' }}>Receptionist (Patient Billing & Incomes Only)</option>
                    <option value="accountant" {{ old('role', $user->role) === 'accountant' ? 'selected' : '' }}>Accountant (Cost Centers & Operational Expenses)</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Full Access & Audits)</option>
                </select>
            </div>

            <div style="border-top: 1px dashed #e5e7eb; padding-top: 1rem; margin-top: 0.25rem;">
                <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280; text-transform: uppercase; display: block; margin-bottom: 0.75rem;">
                    Change Password (Leave blank to keep current)
                </span>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.25rem;">New Password</label>
                        <input type="password" name="password" placeholder="New password" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.25rem;">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.75rem; font-size: 0.875rem;">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                <a href="{{ route('users.index') }}" style="background: #f3f4f6; color: #374151; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">
                    Cancel
                </a>
                <button type="submit" style="background: #4f46e5; color: #ffffff; border: none; padding: 0.5rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; cursor: pointer;">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection