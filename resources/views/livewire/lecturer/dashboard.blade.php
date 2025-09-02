<?php

use App\Models\CourseLSC as Course;
use App\Models\StudentMark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $stats = [
        'courses' => 0,
        'submissions' => 0,
        'pendingGrades' => 0,
    ];

    public function mount(): void
    {
        $this->stats['courses'] = Course::count();
        $this->stats['submissions'] = 0; // Hook up after submissions are wired per lecturer
        $this->stats['pendingGrades'] = StudentMark::whereNull('total')->count();
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Lecturer Workspace</h2>
        <div class="flex gap-2">
            <flux:button :href="route('lecturer.upload-marks')" wire:navigate variant="primary">Upload Marks</flux:button>
            <flux:button :href="route('lecturer.roster')" wire:navigate variant="outline">Course Rosters</flux:button>
            <flux:button :href="route('lecturer.reports')" wire:navigate variant="ghost">Reports</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Assigned Courses</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $stats['courses'] }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Submissions</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $stats['submissions'] }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Pending Grades</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $stats['pendingGrades'] }}</div>
        </div>
    </div>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
        <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
        <div class="flex flex-wrap gap-2">
            <flux:button :href="route('lecturer.upload-marks')" wire:navigate variant="primary">Upload CA/Exam</flux:button>
            <flux:button :href="route('lecturer.roster')" wire:navigate variant="outline">View Rosters</flux:button>
            <flux:button :href="route('lecturer.reports')" wire:navigate variant="ghost">Performance Reports</flux:button>
        </div>
    </div>
</div>


