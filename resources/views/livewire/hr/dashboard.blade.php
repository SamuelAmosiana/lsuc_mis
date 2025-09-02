<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $staff = 0;
    public int $students = 0;
    public int $openPositions = 0;
    public int $attendanceToday = 0;

    public function mount(): void
    {
        $this->staff = (int) DB::table('staff')->count();
        $this->students = (int) DB::table('student')->count();
        $this->openPositions = 0; // Placeholder – wire to vacancies when available
        $this->attendanceToday = 0; // Placeholder – wire to attendance when available
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Human Resource Dashboard</h2>
        <div class="flex gap-2">
            <flux:button :href="route('hr.attendance')" wire:navigate variant="primary">Attendance</flux:button>
            <flux:button :href="route('hr.salaries')" wire:navigate variant="outline">Salaries</flux:button>
            <flux:button :href="route('hr.staff')" wire:navigate variant="ghost">Staff</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Total Staff</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $staff }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Total Students</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $students }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Open Positions</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $openPositions }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Attendance Today</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $attendanceToday }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('hr.staff')" wire:navigate variant="primary">Manage Staff</flux:button>
                <flux:button :href="route('hr.attendance')" wire:navigate variant="outline">Track Attendance</flux:button>
                <flux:button :href="route('hr.salaries')" wire:navigate variant="ghost">Salaries</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Announcements</h3>
            <ul class="list-disc ps-5 text-sm text-neutral-700 space-y-1">
                <li>HR policies update will be posted soon.</li>
                <li>Monthly attendance review due Friday.</li>
            </ul>
        </div>
    </div>
</div>


