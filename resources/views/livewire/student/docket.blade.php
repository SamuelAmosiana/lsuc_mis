<?php

use App\Models\Student;
use App\Models\CourseRegistration;
use App\Models\Bill;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $student;
    public $user;
    public $program;
    public $outstandingBalance;
    public $registeredCourses;
    public $currentSession;
    public $canPrintDocket;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->student = Student::where('user_id', $this->user->id)
            ->with(['program', 'bills'])
            ->first();
        $this->program = $this->student?->program;
        $this->currentSession = date('Y');
        
        // Calculate outstanding balance
        if ($this->student) {
            $this->outstandingBalance = Bill::where('student_id', $this->student->id)
                ->sum('balance');
        } else {
            $this->outstandingBalance = 0;
        }
        
        // Check if docket can be printed (balance must be 0.00)
        $this->canPrintDocket = $this->outstandingBalance <= 0;
        
        // Get registered courses for current session
        if ($this->student) {
            $this->registeredCourses = CourseRegistration::where('student_id', $this->user->id)
                ->where('status', 'approved')
                ->with('course')
                ->get();
        } else {
            $this->registeredCourses = collect();
        }
    }

    public function printDocket(): void
    {
        if (!$this->canPrintDocket) {
            session()->flash('error', 'Cannot print docket with outstanding balance. Please clear all dues first.');
            return;
        }
        
        // Here you would generate and download the PDF
        // For now, we'll just show a success message
        session()->flash('status', 'Docket generated successfully!');
    }

    public function previewDocket(): void
    {
        // Logic to preview docket
        session()->flash('status', 'Docket preview opened in new tab.');
    }

    public function getRegistrationStatusColor(): string
    {
        if (!$this->student) {
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
        }
        
        return match($this->student->status) {
            'active' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
            'inactive' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
            'suspended' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
            'graduated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
        };
    }

    public function getStudyCategory(): string
    {
        // You might want to add this as a field in the student model
        // For now, we'll assume full-time
        return 'Full Time';
    }

    public function getCurrentYearOfStudy(): string
    {
        if (!$this->student || !$this->student->admission_date) {
            return 'N/A';
        }
        
        $admissionYear = $this->student->admission_date->year;
        $currentYear = date('Y');
        $yearOfStudy = ($currentYear - $admissionYear) + 1;
        
        return 'Year ' . min($yearOfStudy, $this->program?->duration_years ?? 4);
    }
}; ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Student Docket</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View and download your official student registration docket</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($canPrintDocket)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Print Available
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Outstanding Balance
                </span>
            @endif
        </div>
    </div>

    <!-- Status Messages -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($student && $program)
    <!-- Docket Preview -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-neutral-700">
        <!-- Docket Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">LUSAKA SOUTH UNIVERSITY COLLEGE</h3>
                    <p class="text-blue-100 mt-1">STUDENT REGISTRATION DOCKET</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-100">Academic Session</div>
                    <div class="text-lg font-semibold">{{ $currentSession }}</div>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Personal Information -->
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-neutral-600 pb-2">
                        Personal Information
                    </h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white font-mono">{{ $student->student_id }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->name }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">NRC Number:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $student->id_card_number ?? 'N/A' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $student->gender ? ucfirst($student->gender) : 'N/A' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $student->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-neutral-600 pb-2">
                        Academic Information
                    </h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Programme:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $program->name }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Programme Code:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white font-mono">{{ $program->code }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Category of Study:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $this->getStudyCategory() }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Session:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $currentSession }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Intake Year:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $student->batch_year ?? ($student->admission_date ? $student->admission_date->year : 'N/A') }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Year of Study:</div>
                            <div class="col-span-2 text-sm text-gray-900 dark:text-white">{{ $this->getCurrentYearOfStudy() }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration Status:</div>
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getRegistrationStatusColor() }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Status -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-neutral-600 pb-2 mb-4">
                    Financial Status
                </h4>
                <div class="bg-gray-50 dark:bg-neutral-700 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold {{ $outstandingBalance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                {{ number_format($outstandingBalance, 2) }} LSL
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Outstanding Balance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $registeredCourses->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Registered Courses</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold {{ $canPrintDocket ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $canPrintDocket ? 'ELIGIBLE' : 'NOT ELIGIBLE' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Print Status</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registered Courses -->
            @if($registeredCourses->count() > 0)
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-neutral-600 pb-2 mb-4">
                    Registered Courses ({{ $currentSession }} Session)
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                            @foreach($registeredCourses as $index => $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-white">{{ $registration->course->course_id ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $registration->course->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                        Approved
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="border-t border-gray-200 dark:border-neutral-600 pt-6">
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <div>
                        Generated on: {{ now()->format('F j, Y \a\t g:i A') }}
                    </div>
                    <div>
                        This is an official document of Lusaka South University College
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-center space-x-4">
        <flux:button wire:click="previewDocket" variant="outline" size="lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Preview Docket
        </flux:button>
        
        <flux:button wire:click="printDocket" variant="primary" size="lg" :disabled="!$canPrintDocket">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            {{ $canPrintDocket ? 'Print Docket (PDF)' : 'Clear Balance to Print' }}
        </flux:button>
    </div>
    
    @else
    <!-- No Student Record -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-neutral-600">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Student Profile Required</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please complete your student profile to access your docket.</p>
            <div class="mt-6">
                <flux:button :href="route('student.profile')" wire:navigate variant="primary">
                    Complete Profile
                </flux:button>
            </div>
        </div>
    </div>
    @endif

    <!-- Docket Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Docket Information</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <p>• The docket can only be printed when your account balance is 0.00 LSL</p>
                    <p>• This document serves as official proof of your registration</p>
                    <p>• Keep a copy of your docket for your records</p>
                    <p>• Contact the Accounts Office to clear any outstanding balances</p>
                    <p>• For any discrepancies, contact the Registry Office immediately</p>
                </div>
            </div>
        </div>
    </div>
</div>


