@extends('layouts.app')

@section('title', 'Income Records')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Income Records</h1>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordIncomeModal">
                    <i class="fas fa-plus"></i> Record Income
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('accounts.income.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\FinancialCategory::where('type', 'income')->get() as $category)
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
                    <a href="{{ route('accounts.income.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Income Records Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="incomeTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Source</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomes as $income)
                        <tr>
                            <td>{{ $income->date_received->format('M d, Y') }}</td>
                            <td>{{ $income->source }}</td>
                            <td>{{ $income->category->name ?? 'N/A' }}</td>
                            <td class="text-success">M{{ number_format($income->amount, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-income" data-id="{{ $income->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="#" class="btn btn-sm btn-warning edit-income" data-id="{{ $income->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('accounts.income.destroy', $income->id) }}" method="POST" class="d-inline">
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
                            <td colspan="5" class="text-center">No income records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($incomes->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $incomes->firstItem() }} to {{ $incomes->lastItem() }} of {{ $incomes->total() }} entries
                </div>
                <div>
                    {{ $incomes->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('accounts.components.modals.income')

@push('scripts')
<script>
$(document).ready(function() {
    // View income details
    $('.view-income').click(function() {
        var incomeId = $(this).data('id');
        // AJAX call to fetch and display income details
        // Implementation will be added later
    });
    
    // Edit income
    $('.edit-income').click(function(e) {
        e.preventDefault();
        var incomeId = $(this).data('id');
        // AJAX call to load income data into edit form
        // Implementation will be added later
    });
});
</script>
@endpush
@endsection
