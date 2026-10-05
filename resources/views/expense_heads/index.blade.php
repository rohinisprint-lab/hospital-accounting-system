@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Expense Categories & Cost Centers</h2>
            <p class="text-muted mb-0">Manage hospital cost centers, department heads, and expense classifications.</p>
        </div>
        <div>
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary me-2">
                &larr; Expense Ledger
            </a>
            <a href="{{ route('income-heads.index') }}" class="btn btn-outline-primary">
                Income Categories &rarr;
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Form Column --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">Add Cost Center</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('expense-heads.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="head_name" class="form-label fw-semibold">Head / Category Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="head_name" 
                                   id="head_name" 
                                   class="form-control @error('head_name') is-invalid @enderror" 
                                   placeholder="e.g., Medical Supplies, Utilities"
                                   value="{{ old('head_name') }}" 
                                   required>
                            @error('head_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Optional notes regarding this cost center...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            Create Cost Center
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table Column --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">Configured Cost Centers</h5>
                    <span class="badge bg-secondary">Total: {{ $heads->total() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Cost Center Name</th>
                                <th>Status</th>
                                <th>Linked Vouchers</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($heads as $head)
                                <tr>
                                    <td>{{ $head->head_id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $head->head_name }}</div>
                                        @if($head->description)
                                            <small class="text-muted">{{ Str::limit($head->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($head->is_active)
                                            <span class="badge bg-success">ACTIVE</span>
                                        @else
                                            <span class="badge bg-warning text-dark">INACTIVE</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $head->expenses_count ?? 0 }} vouchers
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <form action="{{ route('expense-heads.toggle', $head->head_id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $head->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                    {{ $head->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>

                                            @if(($head->expenses_count ?? 0) === 0)
                                                <form action="{{ route('expense-heads.destroy', $head->head_id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this cost center?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No expense categories found. Create one using the form on the left.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($heads->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $heads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection