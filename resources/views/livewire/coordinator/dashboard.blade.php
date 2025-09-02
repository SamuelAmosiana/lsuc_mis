<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $lecturers = 0;
    public int $programmes = 0;
    public int $courses = 0;
    public int $pendingRegistrations = 0;

    public function mount(): void
    {
        $this->lecturers = (int) DB::table('staff')->count();
        $this->programmes = (int) DB::table('programme')->count();
        $this->courses = (int) DB::table('course')->count();
        $this->pendingRegistrations = 0; // Hook to registrations table when available
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Programmes Coordinator</h2>
        <div class="flex gap-2">
            <flux:button :href="route('coordinator.calendar')" wire:navigate variant="primary">Academic Calendar</flux:button>
            <flux:button :href="route('coordinator.timetables')" wire:navigate variant="outline">Timetables</flux:button>
            <flux:button :href="route('coordinator.registrations')" wire:navigate variant="ghost">Registrations</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Lecturers</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $lecturers }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Programmes</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $programmes }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Courses</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $courses }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Pending Registrations</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $pendingRegistrations }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('coordinator.calendar')" wire:navigate variant="primary">Calendar</flux:button>
                <flux:button :href="route('coordinator.timetables')" wire:navigate variant="outline">Timetables</flux:button>
                <flux:button :href="route('coordinator.registrations')" wire:navigate variant="ghost">Registrations</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Updates</h3>
            <ul class="list-disc ps-5 text-sm text-neutral-700 space-y-1">
                <li>Review course allocations for next term.</li>
                <li>Publish exam timetable by end of week.</li>
            </ul>
        </div>
    </div>
</div>


