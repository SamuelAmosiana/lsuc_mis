<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    // Stub – connect to payments once available
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Income</h2>
    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
        <p class="text-sm text-neutral-700">Record and view income here (to be connected to payments table).</p>
    </div>
</div>


