<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $students = 0;
    public int $staff = 0;
    public int $courses = 0;
    public int $programmes = 0;

    public function mount(): void
    {
        $this->students = (int) DB::table('student')->count();
        $this->staff = (int) DB::table('staff')->count();
        $this->courses = (int) DB::table('course')->count();
        $this->programmes = (int) DB::table('programme')->count();
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Super Admin Dashboard</h2>
        <div class="flex gap-2">
            <flux:button :href="route('admin.users')" wire:navigate variant="primary">Manage Users</flux:button>
            <flux:button :href="route('admin.roles')" wire:navigate variant="outline">Manage Roles</flux:button>
            <flux:button :href="route('admin.settings')" wire:navigate variant="ghost">System Settings</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Students</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $students }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Staff</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $staff }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Programmes</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $programmes }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Courses</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $courses }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">System Controls</h3>
            <p class="text-sm text-neutral-600">Configure roles, permissions, and global settings.</p>
            <div class="mt-3 flex gap-2">
                <flux:button :href="route('admin.users')" wire:navigate variant="primary">Users</flux:button>
                <flux:button :href="route('admin.roles')" wire:navigate variant="outline">Roles</flux:button>
                <flux:button :href="route('admin.settings')" wire:navigate variant="ghost">Settings</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Analytics</h3>
            <p class="text-sm text-neutral-600">System-wide analytics and recent activity will appear here.</p>
            <div class="mt-4 h-40 rounded bg-orange-50"></div>
        </div>
    </div>
</div>


