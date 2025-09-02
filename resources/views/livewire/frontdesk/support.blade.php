<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $tickets = [];

    #[Validate('required|string|max:150')]
    public string $subject = '';

    #[Validate('required|string|min:3')]
    public string $details = '';

    public function open(): void
    {
        $this->validate();
        $this->tickets[] = ['subject' => $this->subject, 'details' => $this->details, 'status' => 'open'];
        $this->subject = '';
        $this->details = '';
        session()->flash('status', 'Support ticket opened');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Support & Complaints</h2>

    <form class="grid gap-4 max-w-2xl" wire:submit="open">
        <flux:input wire:model="subject" label="Subject" required />
        <flux:textarea wire:model="details" label="Details" required />
        <div>
            <flux:button type="submit" variant="primary">Open Ticket</flux:button>
        </div>
    </form>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Subject</th>
                    <th class="p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $t['subject'] }}</td>
                        <td class="p-2">{{ $t['status'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="2">No tickets.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


