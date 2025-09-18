<?php

use App\Models\StudentMark;
use App\Models\Student;
use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.app')] class extends Component {
    public $student;
    public $program;
    public $currentYearResults;
    public $allResults;
    public $academicSessions;
    public $selectedSession;
    public $cgpa;
    public $totalCreditsEarned;

    public function mount(): void
    {
        $this->student = Student::where('user_id', auth()->id())->with('program')->first();
        $this->program = $this->student?->program;
        $this->selectedSession = date('Y'); // Current year as default
        
        $this->loadResults();
    }

    public function loadResults(): void
    {
        if (!$this->student) {
            $this->currentYearResults = collect();
            $this->allResults = collect();
            $this->academicSessions = [];
            return;
        }

        // Get all results for the student
        $allMarks = StudentMark::where('student_id', auth()->id())
            ->with(['course'])
            ->orderBy('year', 'desc')
            ->orderBy('term', 'desc')
            ->get();

        $this->allResults = $allMarks;
        
        // Get unique academic sessions
        $this->academicSessions = $allMarks->pluck('year')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Filter results for current year
        $this->currentYearResults = $allMarks->where('year', $this->selectedSession);
        
        // Calculate CGPA and credits
        $this->calculateAcademicMetrics();
    }

    public function updatedSelectedSession(): void
    {
        $this->currentYearResults = $this->allResults->where('year', $this->selectedSession);
    }

    private function calculateAcademicMetrics(): void
    {
        $completedResults = $this->allResults->whereNotNull('total')->where('total', '>=', 50);
        
        if ($completedResults->isEmpty()) {
            $this->cgpa = 0;
            $this->totalCreditsEarned = 0;
            return;
        }

        // Calculate CGPA (assuming each course is worth 3 credits and using 4.0 scale)
        $totalGradePoints = 0;
        $totalCredits = 0;
        
        foreach ($completedResults as $result) {
            $gradePoint = $this->getGradePoint($result->total);
            $credits = 3; // Default credits per course
            $totalGradePoints += $gradePoint * $credits;
            $totalCredits += $credits;
        }
        
        $this->cgpa = $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
        $this->totalCreditsEarned = $totalCredits;
    }

    private function getGradePoint($score): float
    {
        if ($score >= 80) return 4.0; // A
        if ($score >= 70) return 3.0; // B
        if ($score >= 60) return 2.0; // C
        if ($score >= 50) return 1.0; // D
        return 0.0; // F
    }

    private function getLetterGrade($score): string
    {
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }

    private function getGradeComment($score): string
    {
        if ($score >= 50) return 'Clear Pass';
        return 'Repeat Course';
    }

    private function getGradeColor($score): string
    {
        if ($score >= 80) return 'text-green-600 dark:text-green-400';
        if ($score >= 70) return 'text-blue-600 dark:text-blue-400';
        if ($score >= 60) return 'text-yellow-600 dark:text-yellow-400';
        if ($score >= 50) return 'text-orange-600 dark:text-orange-400';
        return 'text-red-600 dark:text-red-400';
    }

    public function getCGPAClassification(): string
    {
        if ($this->cgpa >= 3.5) return 'First Class Honours';
        if ($this->cgpa >= 3.0) return 'Second Class Honours (Upper Division)';
        if ($this->cgpa >= 2.5) return 'Second Class Honours (Lower Division)';
        if ($this->cgpa >= 2.0) return 'Pass Degree';
        return 'Probation';
    }

    public function getSessionGPA($year): float
    {
        $sessionResults = $this->allResults->where('year', $year)->whereNotNull('total');
        
        if ($sessionResults->isEmpty()) {
            return 0;
        }

        $totalGradePoints = 0;
        $totalCredits = 0;
        
        foreach ($sessionResults as $result) {
            $gradePoint = $this->getGradePoint($result->total);
            $credits = 3; // Default credits per course
            $totalGradePoints += $gradePoint * $credits;
            $totalCredits += $credits;
        }
        
        return $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
    }

    public function getYearOfStudy($year): string
    {
        if (!$this->student || !$this->student->admission_date) {
            return 'N/A';
        }
        
        $admissionYear = $this->student->admission_date->year;
        $yearOfStudy = ($year - $admissionYear) + 1;
        
        return 'Year ' . min($yearOfStudy, $this->program?->duration_years ?? 4);
    }
}; ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Academic Results</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View your CA and exam results with GPA computation</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400">Cumulative GPA</div>
            <div class="text-2xl font-bold {{ $cgpa >= 3.0 ? 'text-green-600 dark:text-green-400' : ($cgpa >= 2.0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                {{ number_format($cgpa, 2) }}/4.0
            </div>
        </div>
    </div>

    @if($student && $program)
    <!-- Academic Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">CGPA</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($cgpa, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Credits Earned</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalCreditsEarned }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Classification</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->getCGPAClassification() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Courses</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $allResults->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Selector -->
    @if(count($academicSessions) > 0)
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Select Academic Session</h3>
            <div class="flex items-center space-x-4">
                <label for="session-select" class="text-sm font-medium text-gray-700 dark:text-gray-300">Academic Year:</label>
                <select id="session-select" wire:model.live="selectedSession" class="rounded-md border-gray-300 dark:border-neutral-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($academicSessions as $session)
                    <option value="{{ $session }}">{{ $session }}/{{ $session + 1 }} Session</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- Current Session Results -->
    @if($currentYearResults->count() > 0)
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedSession }} Session Results</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">CA and Exam results for the selected session</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Session GPA</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($this->getSessionGPA($selectedSession), 2) }}</div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CA Score</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Exam Score</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Comment</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($currentYearResults as $result)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-white">
                            {{ $result->course_id }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $result->course->name ?? 'Course ' . $result->course_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-white">
                            {{ $result->ca_score ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-white">
                            {{ $result->exam_score ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold {{ $result->total ? $this->getGradeColor($result->total) : 'text-gray-500' }}">
                            {{ $result->total ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($result->total)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $result->total >= 50 ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                                {{ $this->getLetterGrade($result->total) }}
                            </span>
                            @else
                            <span class="text-sm text-gray-500 dark:text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                            @if($result->total)
                            <span class="{{ $result->total >= 50 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $this->getGradeComment($result->total) }}
                            </span>
                            @else
                            <span class="text-gray-500 dark:text-gray-400">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($allResults->isEmpty())
    <!-- No Results -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-neutral-600">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No Results Available</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Your academic results will appear here once they are published.</p>
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
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please complete your student profile to access your results.</p>
            <div class="mt-6">
                <flux:button :href="route('student.profile')" wire:navigate variant="primary">
                    Complete Profile
                </flux:button>
            </div>
        </div>
    </div>
    @endif
</div>


