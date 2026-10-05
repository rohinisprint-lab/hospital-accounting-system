@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0;">Hospital Branches & Units</h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Manage hospital campuses, specialty clinics, and regional collection centers.</p>
        </div>
        <a href="{{ route('branches.create') }}" style="background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700;">
            + Add New Branch
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
                    <th style="padding: 0.75rem 1rem;">Branch Name</th>
                    <th style="padding: 0.75rem 1rem;">Location / Address</th>
                    <th style="padding: 0.75rem 1rem;">Contact Phone</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Activity Counts</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 0.75rem 1rem; font-weight: 700; color: #111827;">
                            {{ $branch->branch_name }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">
                            {{ $branch->location ?? 'Main Campus' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; color: #4b5563;">
                            {{ $branch->phone ?? '—' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: center;">
                            <span style="background: #ecfdf5; color: #065f46; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; margin-right: 0.25rem;">
                                {{ $branch->incomes_count ?? 0 }} Incomes
                            </span>
                            <span style="background: #fef2f2; color: #991b1b; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                                {{ $branch->expenses_count ?? 0 }} Expenses
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: inline-flex; gap: 0.75rem; align-items: center;">
                                <a href="{{ route('branches.edit', $branch) }}" style="color: #4f46e5; text-decoration: none; font-size: 0.75rem; font-weight: 700;">Edit</a>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Delete this branch?');" style="margin: 0; display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.75rem; font-weight: 600; cursor: pointer; padding: 0;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #9ca3af;">No hospital branches configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($branches->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $branches->links() }}
            </div>
        @endif
    </div>

</div>
@endsection