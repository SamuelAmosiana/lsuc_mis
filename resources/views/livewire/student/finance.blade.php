<?php

use App\Models\Student;
use App\Models\Bill;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $student;
    public $bills;
    public $payments;
    public $totalAmountDue;
    public $totalAmountPaid;
    public $outstandingBalance;
    public $currentYearBalance;

    public function mount(): void
    {
        $this->student = Student::where('user_id', auth()->id())->first();
        
        if ($this->student) {
            $this->bills = Bill::where('student_id', $this->student->id)
                ->with(['payments', 'studentFees'])
                ->orderBy('academic_year', 'desc')
                ->orderBy('term', 'desc')
                ->get();
                
            $this->payments = Payment::whereIn('bill_id', $this->bills->pluck('id'))
                ->with(['bill'])
                ->orderBy('payment_date', 'desc')
                ->get();
                
            $this->calculateFinancials();
        }
    }

    private function calculateFinancials(): void
    {
        $this->totalAmountDue = $this->bills->sum('total_amount');
        $this->totalAmountPaid = $this->bills->sum('amount_paid');
        $this->outstandingBalance = $this->bills->sum('balance');
        
        // Current academic year balance
        $currentYear = date('Y');
        $this->currentYearBalance = $this->bills
            ->where('academic_year', $currentYear)
            ->sum('balance');
    }

    public function getPaymentStatusColor($status): string
    {
        return match($status) {
            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
            'partially_paid' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
            'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
        };
    }
}; ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Financial Information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View your payment history and current balance</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400">Outstanding Balance</div>
            <div class="text-2xl font-bold {{ $outstandingBalance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                {{ number_format($outstandingBalance, 2) }} LSL
            </div>
        </div>
    </div>

    @if($student)
    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount Due</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($totalAmountDue, 2) }} LSL</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount Paid</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($totalAmountPaid, 2) }} LSL</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding Balance</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($outstandingBalance, 2) }} LSL</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Year Balance</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($currentYearBalance, 2) }} LSL</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills & Payment History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Bills -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bills & Statements</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Your academic fee statements by year and term</p>
            </div>
            
            @if($bills->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-neutral-700">
                @foreach($bills as $bill)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $bill->academic_year }} - {{ ucfirst($bill->term) }} Term
                                </h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getPaymentStatusColor($bill->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $bill->status)) }}
                                </span>
                            </div>
                            <div class="mt-2 grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Total:</span>
                                    <span class="ml-1 font-medium text-gray-900 dark:text-white">{{ number_format($bill->total_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Paid:</span>
                                    <span class="ml-1 font-medium text-green-600 dark:text-green-400">{{ number_format($bill->amount_paid, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Balance:</span>
                                    <span class="ml-1 font-medium {{ $bill->balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ number_format($bill->balance, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Bill #{{ $bill->bill_number }} • Due: {{ $bill->due_date->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Bills Found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No billing statements available.</p>
            </div>
            @endif
        </div>

        <!-- Payment History -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment History</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Your recent payment transactions</p>
            </div>
            
            @if($payments->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-neutral-700 max-h-96 overflow-y-auto">
                @foreach($payments as $payment)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    Payment #{{ $payment->id }}
                                </h4>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                    +{{ number_format($payment->amount, 2) }} LSL
                                </span>
                            </div>
                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $payment->bill->academic_year }} - {{ ucfirst($payment->bill->term) }} Term
                            </div>
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $payment->payment_date->format('M j, Y') }}</span>
                                <span>{{ ucfirst($payment->payment_method) }}</span>
                                @if($payment->reference_number)
                                <span>Ref: {{ $payment->reference_number }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Payments Found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No payment history available.</p>
            </div>
            @endif
        </div>
    </div>
    @else
    <!-- No Student Record -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-neutral-600">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Student Profile Not Found</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please complete your student profile to view financial information.</p>
        </div>
    </div>
    @endif

    <!-- Financial Information Notice -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Payment Information</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <p>• All payments should be made through the Accounts Office</p>
                    <p>• Keep your payment receipts for record purposes</p>
                    <p>• Contact the Accounts Office for payment inquiries</p>
                    <p>• Outstanding balances may affect course registration and result access</p>
                </div>
            </div>
        </div>
    </div>
</div>