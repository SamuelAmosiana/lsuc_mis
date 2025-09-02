<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $events = [];
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Academic Calendar</h2>
    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
        <p class="text-sm text-neutral-700">Add term dates, exam periods, and holidays here.</p>
    </div>
</div>


