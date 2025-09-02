<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $items = 0;
    public int $available = 0;
    public int $loans = 0;
    public int $overdue = 0;

    public function mount(): void
    {
        $this->items = (int) DB::table('library_items')->count();
        $this->available = (int) DB::table('library_items')->sum('available_copies');
        $this->loans = (int) DB::table('library_loans')->count();
        $this->overdue = (int) DB::table('library_loans')->whereNull('returned_at')->where('due_at', '<', now()->toDateString())->count();
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Library Dashboard</h2>
        <div class="flex gap-2">
            <flux:button :href="route('librarian.inventory')" wire:navigate variant="primary">Inventory</flux:button>
            <flux:button :href="route('librarian.loans')" wire:navigate variant="outline">Loans</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Items</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $items }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Available Copies</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $available }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Loans</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $loans }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Overdue</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $overdue }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('librarian.inventory')" wire:navigate variant="primary">Manage Books/Media</flux:button>
                <flux:button :href="route('librarian.loans')" wire:navigate variant="outline">Track Loans</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Notices</h3>
            <ul class="list-disc ps-5 text-sm text-neutral-700 space-y-1">
                <li>Check overdue loans weekly.</li>
                <li>Update inventory counts regularly.</li>
            </ul>
        </div>
    </div>
</div>


