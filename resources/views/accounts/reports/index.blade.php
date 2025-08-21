@extends('layouts.app')

@section('title', 'Financial Reports')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.css">
<style>
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 30px;
    }
    .report-card {
        transition: transform 0.3s;
    }
    .report-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Financial Reports</h1>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('accounts.reports.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Generate Report</button>
                    <a href="{{ route('accounts.reports.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Income</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($incomeByCategory->sum('incomes_sum_amount'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($expenseByCategory->sum('expenses_sum_amount'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            @php
                $netIncome = $incomeByCategory->sum('incomes_sum_amount') - $expenseByCategory->sum('expenses_sum_amount');
            @endphp
            <div class="card border-left-{{ $netIncome >= 0 ? 'info' : 'danger' }} shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $netIncome >= 0 ? 'info' : 'danger' }} text-uppercase mb-1">
                                Net Income</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">M{{ number_format($netIncome, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Income vs Expenses Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Income vs Expenses</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="incomeExpenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Income by Category -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Income by Category</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="incomeByCategoryChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalIncome = $incomeByCategory->sum('incomes_sum_amount') @endphp
                                    @foreach($incomeByCategory as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>M{{ number_format($category->incomes_sum_amount, 2) }}</td>
                                        <td>{{ $totalIncome > 0 ? number_format(($category->incomes_sum_amount / $totalIncome) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses by Category -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Expenses by Category</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="expenseByCategoryChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalExpense = $expenseByCategory->sum('expenses_sum_amount') @endphp
                                    @foreach($expenseByCategory as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>M{{ number_format($category->expenses_sum_amount, 2) }}</td>
                                        <td>{{ $totalExpense > 0 ? number_format(($category->expenses_sum_amount / $totalExpense) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
// Income vs Expenses Chart
const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
const incomeExpenseChart = new Chart(incomeExpenseCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($cashFlow)->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))) !!},
        datasets: [
            {
                label: 'Income',
                data: {!! json_encode(collect($cashFlow)->pluck('income')) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Expenses',
                data: {!! json_encode(collect($cashFlow)->pluck('expense')) !!},
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Net',
                data: {!! json_encode(collect($cashFlow)->pluck('net')) !!},
                borderColor: '#1cc88a',
                backgroundColor: 'transparent',
                borderDash: [5, 5],
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': M' + context.raw.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'M' + value.toLocaleString('en-US');
                    }
                }
            }
        }
    }
});

// Income by Category Chart
const incomeCategoryCtx = document.getElementById('incomeByCategoryChart').getContext('2d');
const incomeCategoryChart = new Chart(incomeCategoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($incomeByCategory->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($incomeByCategory->pluck('incomes_sum_amount')) !!},
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
                '#5a5c69', '#858796', '#5a5c69', '#36b9cc', '#1cc88a'
            ],
            hoverBackgroundColor: [
                '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                '#373840', '#6b6d7d', '#373840', '#2c9faf', '#17a673'
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: M${value.toLocaleString('en-US', {minimumFractionDigits: 2})} (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '70%',
    },
});

// Expense by Category Chart
const expenseCategoryCtx = document.getElementById('expenseByCategoryChart').getContext('2d');
const expenseCategoryChart = new Chart(expenseCategoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($expenseByCategory->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($expenseByCategory->pluck('expenses_sum_amount')) !!},
            backgroundColor: [
                '#e74a3b', '#f6c23e', '#36b9cc', '#4e73df', '#1cc88a',
                '#5a5c69', '#858796', '#5a5c69', '#36b9cc', '#1cc88a'
            ],
            hoverBackgroundColor: [
                '#be2617', '#dda20a', '#2c9faf', '#2e59d9', '#17a673',
                '#373840', '#6b6d7d', '#373840', '#2c9faf', '#17a673'
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: M${value.toLocaleString('en-US', {minimumFractionDigits: 2})} (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '70%',
    },
});
</script>
@endpush
@endsection
