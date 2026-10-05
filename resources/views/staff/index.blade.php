@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">Staff & User Management</h2>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Control system users, assigned branches, and access privileges.</p>
    </div>
    <a href="{{ route('staff.create') }}" style="background: #4f46e5; color: white; padding: 0.625rem 1.25rem; border-radius: 0.375rem; text-decoration: none; font-weight: 700; font-size: 0.875rem;">
        + Add New Staff
    </a>
</div>

<div style="background: white; border-radius: 0.75rem; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 0.75rem; text-transform: uppercase;">
                <th style="padding: 0.75rem 1rem;">Name</th>
                <th style="padding: 0.75rem 1rem;">Email</th>
                <th style="padding: 0.75rem 1rem;">Role</th>
                <th style="padding: 0.75rem 1rem;">Assigned Branch</th>
                <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $user)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 0.875rem 1rem; font-weight: 600; color: #1e293b;">{{ $user->name }}</td>
                    <td style="padding: 0.875rem 1rem; color: #64748b;">{{ $user->email }}</td>
                    <td style="padding: 0.875rem 1rem;">
                        <span style="display: inline-block; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background: {{ strtolower($user->role) === 'admin' ? '#ede9fe; color: #6d28d9;' : '#f1f5f9; color: #475569;' }}">
                            {{ strtoupper($user->role ?? 'STAFF') }}
                        </span>
                    </td>
                    <td style="padding: 0.875rem 1rem; color: #334155;">{{ $user->branch->branch_name ?? 'All Branches' }}</td>
                    <td style="padding: 0.875rem 1rem; text-align: right;">
                        @if($user->id !== auth()->id())
                            <form action="{{ route('staff.destroy', $user) }}" method="POST" onsubmit="return confirm('Remove this staff user?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 600; cursor: pointer; font-size: 0.8125rem;">
                                    Delete
                                </button>
                            </form>
                        @else
                            <span style="font-size: 0.75rem; color: #94a3b8; font-style: italic;">Current User</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">No staff members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection