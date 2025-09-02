<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public string $message = '';

    public function send(): void
    {
        // Stub: integrate with notifications / email
        session()->flash('status', 'Message queued');
        $this->message = '';
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Enrollment Communications</h2>

    <form class="max-w-2xl grid gap-4" wire:submit="send">
        <flux:textarea wire:model="message" label="Message" placeholder="Type an update for applicants..." />
        <div>
            <flux:button type="submit" variant="primary">Send</flux:button>
        </div>
    </form>
</div>


