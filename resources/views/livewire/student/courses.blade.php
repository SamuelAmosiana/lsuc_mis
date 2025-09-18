<?php

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\Program;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.app')] class extends Component {
    public $student;
    public $program;
    public $availableCourses;
    public $registeredCourses;
    public $pendingRegistrations;
    public array $selectedCourses = [];
    public $currentSession;
    public $registrationOpen = true;

    public function mount(): void
    {
        $this->student = Student::where('user_id', auth()->id())->with('program')->first();
        $this->program = $this->student?->program;
        $this->currentSession = date('Y'); // You might want to get this from a settings table
        
        $this->loadCourses();
    }

    public function loadCourses(): void
    {
        if (!$this->student || !$this->program) {
            $this->availableCourses = collect();
            $this->registeredCourses = collect();
            $this->pendingRegistrations = collect();
            return;
        }

        // Get all registrations for this student
        $allRegistrations = CourseRegistration::where('student_id', auth()->id())
            ->with(['course'])
            ->get();
            
        // Separate by status
        $this->registeredCourses = $allRegistrations->where('status', 'approved');
        $this->pendingRegistrations = $allRegistrations->where('status', 'pending');
        
        // Get courses already registered or pending
        $excludedCourseIds = $allRegistrations->pluck('course_id')->toArray();
        
        // Get available courses for the program (you might need to adjust this based on your course-program relationship)
        $this->availableCourses = Course::whereNotIn('course_id', $excludedCourseIds)
            ->orderBy('name')
            ->get();
    }

    public function register(): void
    {
        if (empty($this->selectedCourses)) {
            session()->flash('error', 'Please select at least one course to register.');
            return;
        }

        if (!$this->student) {
            session()->flash('error', 'Student profile not found. Please complete your profile first.');
            return;
        }

        try {
            DB::transaction(function () {
                foreach ($this->selectedCourses as $courseId) {
                    CourseRegistration::create([
                        'student_id' => auth()->id(),
                        'course_id' => $courseId,
                        'status' => 'pending'
                    ]);
                }
            });

            $this->selectedCourses = [];
            $this->loadCourses();
            
            session()->flash('status', 'Course registration submitted successfully! Awaiting approval from Programme Coordinator.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit course registration. Please try again.');
        }
    }

    public function withdrawRegistration($registrationId): void
    {
        try {
            $registration = CourseRegistration::where('id', $registrationId)
                ->where('student_id', auth()->id())
                ->where('status', 'pending')
                ->first();
                
            if ($registration) {
                $registration->delete();
                $this->loadCourses();
                session()->flash('status', 'Course registration withdrawn successfully.');
            } else {
                session()->flash('error', 'Registration not found or cannot be withdrawn.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to withdraw registration. Please try again.');
        }
    }

    public function getStatusColor($status): string
    {
        return match($status) {
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
        };
    }
}; ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Course Registration</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Register for courses and track your enrollment status</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400">Academic Session</div>
            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $currentSession }}</div>
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
    <!-- Program Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Programme Information</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <p><strong>Programme:</strong> {{ $program->name }} ({{ $program->code }})</p>
                    <p><strong>Department:</strong> {{ $program->department }}</p>
                    <p><strong>Duration:</strong> {{ $program->duration_years }} years ({{ $program->total_semesters }} semesters)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Registered Courses -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Registered Courses</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Courses you have successfully enrolled in</p>
            </div>
            
            @if($registeredCourses->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-neutral-700">
                @foreach($registeredCourses as $registration)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $registration->course->name ?? 'Course Name' }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Course Code: {{ $registration->course->course_id ?? 'N/A' }}
                            </p>
                            <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <span>Registered: {{ $registration->created_at->format('M j, Y') }}</span>
                                @if($registration->approved_at)
                                <span class="ml-4">Approved: {{ $registration->approved_at->format('M j, Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusColor($registration->status) }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Registered Courses</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't registered for any courses yet.</p>
            </div>
            @endif
        </div>

        <!-- Pending Registrations -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Approvals</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Courses awaiting coordinator approval</p>
            </div>
            
            @if($pendingRegistrations->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-neutral-700">
                @foreach($pendingRegistrations as $registration)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $registration->course->name ?? 'Course Name' }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Course Code: {{ $registration->course->course_id ?? 'N/A' }}
                            </p>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>Submitted: {{ $registration->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusColor($registration->status) }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                            <button wire:click="withdrawRegistration({{ $registration->id }})" 
                                    wire:confirm="Are you sure you want to withdraw this registration?"
                                    class="inline-flex items-center p-1 border border-transparent rounded text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Pending Registrations</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All your registrations have been processed.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Course Registration Form -->
    @if($registrationOpen && $availableCourses->count() > 0)
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Register for New Courses</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Select courses you want to register for this session</p>
        </div>
        
        <form wire:submit="register" class="p-6">
            <div class="space-y-4 mb-6">
                @foreach($availableCourses as $course)
                <label class="flex items-start space-x-3 p-4 border border-gray-200 dark:border-neutral-600 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-700/50 cursor-pointer">
                    <input type="checkbox" wire:model="selectedCourses" value="{{ $course->course_id }}" 
                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $course->name }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Course Code: {{ $course->course_id }}
                        </div>
                        @if(isset($course->credits))
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            Credits: {{ $course->credits }}
                        </div>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
            
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-neutral-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Selected: <span class="font-medium">{{ count($selectedCourses) }}</span> course(s)
                </div>
                <flux:button type="submit" variant="primary" :disabled="empty($selectedCourses)">
                    Submit Registration
                </flux:button>
            </div>
        </form>
    </div>
    @elseif($availableCourses->count() === 0)
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-neutral-600">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">All Courses Registered</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">You have registered for all available courses in your programme.</p>
        </div>
    </div>
    @endif

    @else
    <!-- No Student Profile -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-neutral-600">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Student Profile Required</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please complete your student profile to access course registration.</p>
            <div class="mt-6">
                <flux:button :href="route('student.profile')" wire:navigate variant="primary">
                    Complete Profile
                </flux:button>
            </div>
        </div>
    </div>
    @endif

    <!-- Registration Guidelines -->
    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">Course Registration Guidelines</h3>
                <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                    <p>• All course registrations require approval from your Programme Coordinator</p>
                    <p>• Ensure you meet any prerequisites before registering for advanced courses</p>
                    <p>• Registration deadlines are strictly enforced</p>
                    <p>• Outstanding fees may prevent course registration</p>
                    <p>• Contact your Programme Coordinator for course guidance</p>
                </div>
            </div>
        </div>
    </div>
</div>


