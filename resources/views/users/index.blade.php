@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Staff & Access Control</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Manage hospital administrative, billing, and accounting user accounts.</p>
        </div>
        <a href="{{ route('users.create') }}" style="background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700;">
            + Add New Staff
        </a>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; border: 1px solid #a7f3d0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; border: 1px solid #fecdd3;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: #ffffff; border-radius: 0.75rem; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 0.75rem 1rem;">Staff Name</th>
                    <th style="padding: 0.75rem 1rem;">Official Email</th>
                    <th style="padding: 0.75rem 1rem;">Assigned Role</th>
                    <th style="padding: 0.75rem 1rem;">Joined Date</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 0.75rem 1rem; font-weight: 700; color: #111827;">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span style="font-size: 0.6875rem; background: #e0e7ff; color: #3730a3; padding: 0.15rem 0.4rem; border-radius: 9999px; margin-left: 0.25rem;">You</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">{{ $user->email }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            @php
                                $badgeStyles = match($user->role) {
                                    'admin' => 'background: #fef2f2; color: #991b1b;',
                                    'accountant' => 'background: #eff6ff; color: #1d4ed8;',
                                    default => 'background: #ecfdf5; color: #065f46;',
                                };
                            @endphp
                            <span style="{{ $badgeStyles }} padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #6b7280; font-size: 0.8125rem;">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : 'Pre-configured' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: inline-flex; gap: 0.75rem; align-items: center;">
                                <a href="{{ route('users.edit', $user) }}" style="color: #4f46e5; text-decoration: none; font-size: 0.75rem; font-weight: 700;">Edit</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Remove access for this staff user?');" style="margin: 0; display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.75rem; font-weight: 600; cursor: pointer; padding: 0;">
                                            Remove
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #9ca3af;">No staff records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection