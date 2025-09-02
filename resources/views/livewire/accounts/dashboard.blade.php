<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public float $tuition = 0;
    public float $otherFees = 0;
    public float $payments = 0;
    public int $studentsWithBalances = 0;

    public function mount(): void
    {
        $this->tuition = (float) DB::table('fee')->where('name', 'Tuition')->sum('amount');
        $this->otherFees = (float) DB::table('fee')->where('name', '!=', 'Tuition')->sum('amount');
        $this->payments = 0.0; // Hook to payments table if/when added
        $this->studentsWithBalances = (int) DB::table('student')->count(); // Placeholder
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Accounts Dashboard</h2>
        <div class="flex gap-2">
            <flux:button :href="route('accounts.fees')" wire:navigate variant="primary">Fees</flux:button>
            <flux:button :href="route('accounts.income')" wire:navigate variant="outline">Income</flux:button>
            <flux:button :href="route('accounts.expenses')" wire:navigate variant="ghost">Expenses</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Tuition (Configured)</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ number_format($tuition, 2) }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Other Fees</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ number_format($otherFees, 2) }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Payments (This Term)</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ number_format($payments, 2) }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Students With Balances</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $studentsWithBalances }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('accounts.fees')" wire:navigate variant="primary">Manage Fees</flux:button>
                <flux:button :href="route('accounts.income')" wire:navigate variant="outline">Record Income</flux:button>
                <flux:button :href="route('accounts.expenses')" wire:navigate variant="ghost">Record Expense</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Reports</h3>
            <p class="text-sm text-neutral-700">Download period summaries and outstanding balances.</p>
            <div class="mt-3 flex gap-2">
                <flux:button :href="route('accounts.reports')" wire:navigate variant="outline">View Reports</flux:button>
            </div>
        </div>
    </div>
</div>


