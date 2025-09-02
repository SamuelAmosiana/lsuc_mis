<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Docket & Reports</h2>
    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
        <p class="text-sm text-neutral-700">Download your student docket and reports here.</p>
        <div class="mt-3 flex gap-2">
            <flux:button variant="outline">Download Docket (PDF)</flux:button>
            <flux:button variant="outline">Results Statement (PDF)</flux:button>
        </div>
    </div>
</div>


