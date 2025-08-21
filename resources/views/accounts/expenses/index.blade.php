@extends('layouts.app')

@section('title', 'Expense Records')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Expense Records</h1>
                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#recordExpenseModal">
                    <i class="fas fa-plus"></i> Record Expense
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('accounts.expenses.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\FinancialCategory::where('type', 'expense')->get() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('accounts.expenses.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Expense Records Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="expenseTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Payee</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date_paid->format('M d, Y') }}</td>
                            <td>{{ $expense->payee }}</td>
                            <td>{{ $expense->category->name ?? 'N/A' }}</td>
                            <td class="text-danger">M{{ number_format($expense->amount, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-expense" data-id="{{ $expense->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="#" class="btn btn-sm btn-warning edit-expense" data-id="{{ $expense->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('accounts.expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No expense records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($expenses->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $expenses->firstItem() }} to {{ $expenses->lastItem() }} of {{ $expenses->total() }} entries
                </div>
                <div>
                    {{ $expenses->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('accounts.components.modals.expense')

@push('scripts')
<script>
$(document).ready(function() {
    // View expense details
    $('.view-expense').click(function() {
        var expenseId = $(this).data('id');
        // AJAX call to fetch and display expense details
        // Implementation will be added later
    });
    
    // Edit expense
    $('.edit-expense').click(function(e) {
        e.preventDefault();
        var expenseId = $(this).data('id');
        // AJAX call to load expense data into edit form
        // Implementation will be added later
    });
});
</script>
@endpush
@endsection
