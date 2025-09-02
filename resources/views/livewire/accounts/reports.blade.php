<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Accounts Reports</h2>
    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
        <p class="text-sm text-neutral-700">Download fee summaries, income vs expense, and outstanding balance reports.</p>
        <div class="mt-3 flex gap-2">
            <flux:button variant="outline">Term Summary</flux:button>
            <flux:button variant="outline">Outstanding Balances</flux:button>
        </div>
    </div>
</div>


