@extends('layouts.app')

@section('title', 'Accounts Dashboard')

@push('styles')
<style>
    .stat-card {
        border-left: 5px solid;
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Accounts Dashboard</h1>
        </div>
    </div>

    @include('accounts.components.stats-cards')
    
    <div class="row">
        @include('accounts.components.recent-transactions')
        @include('accounts.components.unpaid-bills')
    </div>
    
    @include('accounts.components.quick-actions')
    
    @include('accounts.components.modals.income')
    @include('accounts.components.modals.expense')
</div>
@endsection
