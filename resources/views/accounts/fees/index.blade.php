@extends('accounts.layout')

@push('styles')
<style>
    .status-badge { font-size: 0.8rem; padding: 0.35em 0.65em; }
    .student-avatar { width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; }
    .filter-section { background-color: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; }
</style>
@endpush

@push('tabs')
    <a href="{{ route('accounts.dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300">
        Dashboard
    </a>
    <a href="{{ route('accounts.income.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300">
        Income
    </a>
    <a href="{{ route('accounts.expenses.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300">
        Expenses
    </a>
    <a href="{{ route('accounts.fees.index') }}" class="px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-500">
        Student Fees
    </a>
    <a href="{{ route('accounts.reports') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300">
        Reports
    </a>
@endpush

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Fees</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">M{{ number_format($fees->sum('amount'), 2) }}</div>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paid</div>
            <div class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">M{{ number_format($fees->sum('amount_paid'), 2) }}</div>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding</div>
            <div class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400">
                M{{ number_format($fees->sum('amount') - $fees->sum('amount_paid'), 2) }}
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue</div>
            <div class="mt-1 text-2xl font-semibold text-amber-600 dark:text-amber-400">
                M{{ number_format($fees->where('due_date', '<', now())->where('amount', '>', DB::raw('amount_paid'))->sum(DB::raw('amount - amount_paid')), 2) }}
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-4 mb-6">
        <form action="{{ route('accounts.fees.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                    <select name="student_id" class="w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->student_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                    <select name="due_date" class="w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Time</option>
                        <option value="today" {{ request('due_date') == 'today' ? 'selected' : '' }}>Due Today</option>
                        <option value="week" {{ request('due_date') == 'week' ? 'selected' : '' }}>Due This Week</option>
                        <option value="month" {{ request('due_date') == 'month' ? 'selected' : '' }}>Due This Month</option>
                        <option value="overdue" {{ request('due_date') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                    <a href="{{ route('accounts.fees.index') }}" class="px-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Student Fees Table -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fee Type</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Paid</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Balance</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($fees as $fee)
                    @php
                        $balance = $fee->amount - $fee->amount_paid - $fee->discount;
                        $status = $balance <= 0 ? 'Paid' : ($fee->amount_paid > 0 ? 'Partial' : 'Unpaid');
                        $isOverdue = $fee->due_date < now() && $balance > 0;
                        $rowClass = $isOverdue ? 'bg-red-50 dark:bg-red-900/20' : 'hover:bg-gray-50 dark:hover:bg-zinc-700/50';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700" data-fee-id="{{ $fee->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $fee->student->full_name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $fee->student->student_number }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $fee->fee_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            M{{ number_format($fee->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            M{{ number_format($fee->amount_paid, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $fee->balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            M{{ number_format($fee->balance, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $fee->due_date->format('M d, Y') }}
                            @if($fee->balance > 0 && $fee->due_date->isPast())
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                                    Overdue
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            @if($fee->balance > 0)
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 record-payment"
                                        data-fee-id="{{ $fee->id }}"
                                        data-student="{{ $fee->student->full_name }}"
                                        data-fee-type="{{ $fee->fee_type }}"
                                        data-balance="{{ $balance }}">
                                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Record Payment
                                </button>
                                @endif
                                <button type="button" 
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-zinc-600 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 view-payments"
                                        data-fee-id="{{ $fee->id }}">
                                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View History
                                </button>
                            </div>
                        </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="rounded-full bg-gray-100 dark:bg-zinc-800 p-4">
                                    <svg class="h-10 w-10 text-gray-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No fees found</h3>
                                <p class="max-w-md text-sm text-gray-500 dark:text-gray-400">
                                    There are no fees matching your current filters. Try adjusting your search or create a new fee.
                                </p>
                                <div class="flex space-x-3 pt-2">
                                    <button type="button" onclick="document.querySelector('button[data-headlessui-state][id^='headlessui-popover-button-']').click()" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-zinc-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Adjust filters
                                    </button>
                                    <a href="{{ route('accounts.fees.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        New Fee
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($fees->hasPages())
        <div class="bg-white dark:bg-zinc-800 px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-zinc-700">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Showing <span class="font-medium">{{ $fees->firstItem() }}</span> to 
                        <span class="font-medium">{{ $fees->lastItem() }}</span> of 
                        <span class="font-medium">{{ $fees->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $fees->withQueryString()->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
        @endif
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="recordPaymentModal">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-zinc-700 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" class="rounded-md text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 focus:outline-none" id="closePaymentModal">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Record Payment
                    </h3>
                    <div class="mt-2">
                        <form id="paymentForm" method="POST" class="space-y-4 mt-4">
                            @csrf
                            <div>
                                <label for="studentName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Student</label>
                                <div class="mt-1">
                                    <input type="text" id="studentName" class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="feeType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Fee Type</label>
                                    <div class="mt-1">
                                        <input type="text" id="feeType" class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="outstandingBalance" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Outstanding Balance</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">M</span>
                                        </div>
                                        <input type="text" id="outstandingBalance" class="block w-full pl-7 pr-12 rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Amount to Pay (M)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">M</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" id="amount" name="amount" required
                                           class="block w-full pl-7 pr-12 rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Payment Date</label>
                                    <div class="mt-1">
                                        <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" 
                                               class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                               required>
                                    </div>
                                </div>
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Payment Method</label>
                                    <div class="mt-1">
                                        <select id="payment_method" name="payment_method" 
                                                class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                required>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="check">Check</option>
                                            <option value="mobile_money">Mobile Money</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">Reference/Receipt #</label>
                                <div class="mt-1">
                                    <input type="text" id="reference_number" name="reference_number" 
                                           class="block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           placeholder="Optional reference number">
                                </div>
                            </div>
                            
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="button" id="cancelPayment" class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-zinc-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-1 sm:text-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-2 sm:text-sm">
                                    Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment History Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="paymentHistoryModal">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-zinc-700 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" class="rounded-md text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 focus:outline-none" id="closePaymentHistoryModal">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="paymentHistoryTitle">
                        Payment History
                    </h3>
                    <div class="mt-2">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-600">
                            <thead class="bg-gray-50 dark:bg-zinc-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Method
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Reference #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Recorded By
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody" class="bg-white dark:bg-zinc-700 divide-y divide-gray-200 dark:divide-zinc-600">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment History Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="paymentHistoryModal">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-zinc-700 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" class="rounded-md text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 focus:outline-none" id="closePaymentHistoryModal">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="paymentHistoryTitle">
                        Payment History
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="paymentHistoryFeeType"></p>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Method
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Reference #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Recorded By
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody" class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Loading payment history...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button" id="cancelPaymentHistory" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const recordPaymentModal = document.getElementById('recordPaymentModal');
    const closePaymentModal = document.getElementById('closePaymentModal');
    const cancelPaymentBtn = document.getElementById('cancelPayment');
    
    // Handle record payment button click
    document.querySelectorAll('.record-payment').forEach(button => {
        button.addEventListener('click', function() {
            const feeId = this.dataset.feeId;
            const student = this.dataset.student;
            const feeType = this.dataset.feeType;
            const balance = parseFloat(this.dataset.balance);
            
            // Set form values
            document.getElementById('studentName').value = student;
            document.getElementById('feeType').value = feeType;
            document.getElementById('outstandingBalance').value = balance.toFixed(2);
            document.getElementById('amount').value = balance.toFixed(2);
            document.getElementById('amount').max = balance.toFixed(2);
            document.getElementById('paymentForm').action = `{{ route('accounts.fees.payments.store') }}/${feeId}`;
            
            // Show modal
            recordPaymentModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    });
    
    // Handle close modal buttons
    [closePaymentModal, cancelPaymentBtn].forEach(button => {
        button?.addEventListener('click', function() {
            recordPaymentModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    // Close modal when clicking outside
    recordPaymentModal?.addEventListener('click', function(e) {
        if (e.target === recordPaymentModal) {
            recordPaymentModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
    
    // Handle view payments button click
    document.querySelectorAll('.view-payments').forEach(button => {
        button.addEventListener('click', function() {
            const feeId = this.dataset.feeId;
            const student = this.closest('tr').querySelector('td:first-child div:first-child div:first-child').textContent.trim();
            const feeType = this.closest('tr').querySelector('td:nth-child(2)').textContent.trim();
            
            // Set modal title
            document.getElementById('paymentHistoryTitle').textContent = `Payment History - ${student}`;
            document.getElementById('paymentHistoryFeeType').textContent = feeType;
            
            // Show loading state
            const paymentsTable = document.getElementById('paymentsTableBody');
            paymentsTable.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div class="flex justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading payment history...</span>
                        </div>
                    </td>
                </tr>`;
            
            // Show the modal
            document.getElementById('paymentHistoryModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Fetch payment history
            fetch(`{{ route('accounts.fees.payments.history') }}?fee_id=${feeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        paymentsTable.innerHTML = data.map(payment => `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    M${parseFloat(payment.amount).toFixed(2)}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    ${new Date(payment.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    ${payment.payment_method.charAt(0).toUpperCase() + payment.payment_method.slice(1).replace('_', ' ')}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    ${payment.reference_number || 'N/A'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    ${payment.recorded_by}
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        paymentsTable.innerHTML = `
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No payment history found for this fee.
                                </td>
                            </tr>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching payment history:', error);
                    paymentsTable.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-red-500">
                                Error loading payment history. Please try again.
                            </td>
                        </tr>`;
                });
        });
    });
    
    // Close payment history modal
    const closePaymentHistoryModal = document.getElementById('closePaymentHistoryModal');
    const paymentHistoryModal = document.getElementById('paymentHistoryModal');
    
    [closePaymentHistoryModal, document.getElementById('cancelPaymentHistory')].forEach(button => {
        button?.addEventListener('click', function() {
            paymentHistoryModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });
    });
    
    // Close modal when clicking outside
    paymentHistoryModal?.addEventListener('click', function(e) {
        if (e.target === paymentHistoryModal) {
            paymentHistoryModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
    
    // Handle amount validation
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            const maxAmount = parseFloat(this.max);
            const currentAmount = parseFloat(this.value) || 0;
            
            if (currentAmount > maxAmount) {
                this.value = maxAmount.toFixed(2);
            }
        });
    }
});
</script>
@endpush
@endsection
