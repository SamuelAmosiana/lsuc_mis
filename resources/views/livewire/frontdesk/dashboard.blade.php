<?php

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public int $visitorsToday = 0;
    public int $meetingsToday = 0;
    public int $openTickets = 0;
    public int $newFeedback = 0;

    public function mount(): void
    {
        $this->visitorsToday = 0; // Hook to visitors table later
        $this->meetingsToday = 0; // Hook to meetings table later
        $this->openTickets = 0; // Hook to tickets table later
        $this->newFeedback = 0; // Hook to feedback table later
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Front Desk</h2>
        <div class="flex gap-2">
            <flux:button :href="route('frontdesk.visitors')" wire:navigate variant="primary">Visitors</flux:button>
            <flux:button :href="route('frontdesk.meetings')" wire:navigate variant="outline">Meetings</flux:button>
            <flux:button :href="route('frontdesk.support')" wire:navigate variant="ghost">Support</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Visitors Today</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $visitorsToday }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Meetings Today</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $meetingsToday }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">Open Tickets</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $openTickets }}</div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="text-xs uppercase tracking-wider text-orange-600">New Feedback</div>
            <div class="mt-1 text-3xl font-bold text-zinc-900">{{ $newFeedback }}</div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Quick Actions</h3>
            <div class="flex flex-wrap gap-2">
                <flux:button :href="route('frontdesk.visitors')" wire:navigate variant="primary">Log Visitor</flux:button>
                <flux:button :href="route('frontdesk.meetings')" wire:navigate variant="outline">Log Meeting</flux:button>
                <flux:button :href="route('frontdesk.feedback')" wire:navigate variant="ghost">Capture Feedback</flux:button>
            </div>
        </div>
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <h3 class="mb-2 text-lg font-semibold text-zinc-900">Notices</h3>
            <ul class="list-disc ps-5 text-sm text-neutral-700 space-y-1">
                <li>Remember to tag meeting attendees for HR follow-up.</li>
                <li>Daily visitor summary needed at 17:00.</li>
            </ul>
        </div>
    </div>
</div>


