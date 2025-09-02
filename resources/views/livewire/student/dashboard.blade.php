<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $registeredCourses = 0;
    public float $balance = 0;
    public int $hostelAssigned = 0;
    public int $resultsCount = 0;

    public function mount(): void
    {
        $user = auth()->user();
        // Registered courses (via pivot)
        $this->registeredCourses = (int) DB::table('student_course')->where('student_id', $user->id)->count();
        // Balance placeholder (no ledger yet). Show 0
        $this->balance = 0.0;
        // Accommodation
        $this->hostelAssigned = (int) DB::table('accommodation_assignments')->where('student_id', $user->id)->count();
        // Results
        $this->resultsCount = (int) DB::table('student_mark')->where('student_id', $user->id)->count();
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Student Dashboard</h2>
        <div class="flex gap-2">
            <flux:button :href="route('student.profile')" wire:navigate variant="primary">Profile</flux:button>
            <flux:button :href="route('student.courses')" wire:navigate variant="outline">Register Courses</flux:button>
            <flux:button :href="route('student.results')" wire:navigate variant="ghost">Results</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Registered Courses</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $registeredCourses }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Balance</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ number_format($balance, 2) }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Accommodation</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $hostelAssigned > 0 ? 'Assigned' : 'Not Assigned' }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Results</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $resultsCount }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('student.courses')" wire:navigate variant="primary">Register Courses</flux:button>
                <flux:button :href="route('student.accommodation')" wire:navigate variant="outline">Apply Accommodation</flux:button>
                <flux:button :href="route('student.docket')" wire:navigate variant="ghost">Print Docket</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Announcements</h3>
            <ul class="list-disc ps-5 text-sm text-neutral-700 space-y-1">
                <li>Check your timetable and register early.</li>
                <li>Pay fees on time to avoid penalties.</li>
            </ul>
        </div>
    </div>
</div>


