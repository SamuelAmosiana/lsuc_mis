<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $applicants = 0;
    public int $students = 0;
    public int $pendingInterviews = 0;
    public int $accommodationRequests = 0;

    public function mount(): void
    {
        $this->applicants = 0; // Hook to applications table when available
        $this->students = (int) DB::table('student')->count();
        $this->pendingInterviews = 0; // Placeholder
        $this->accommodationRequests = 0; // Placeholder
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Enrollment Dashboard</h2>
        <div class="flex gap-2">
            <flux:button :href="route('enrollment.applications')" wire:navigate variant="primary">Applications</flux:button>
            <flux:button :href="route('enrollment.accommodation')" wire:navigate variant="outline">Accommodation</flux:button>
            <flux:button :href="route('enrollment.communications')" wire:navigate variant="ghost">Communications</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Applicants</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $applicants }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Students</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $students }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Pending Interviews</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $pendingInterviews }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Accommodation Requests</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $accommodationRequests }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('enrollment.applications')" wire:navigate variant="primary">Manage Applications</flux:button>
                <flux:button :href="route('enrollment.accommodation')" wire:navigate variant="outline">Assign Accommodation</flux:button>
                <flux:button :href="route('enrollment.communications')" wire:navigate variant="ghost">Communications</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Announcements</h3>
            <ul class="list-disc ps-5 text-sm text-neutral-700 space-y-1">
                <li>Next enrollment intake opens next month.</li>
                <li>Prepare interview schedules for pending applicants.</li>
            </ul>
        </div>
    </div>
</div>


