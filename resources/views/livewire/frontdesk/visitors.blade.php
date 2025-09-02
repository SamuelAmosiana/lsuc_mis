<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $rows = [];

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:150')]
    public string $purpose = '';

    public function log(): void
    {
        // Stub: persist to a visitors table
        $this->validate();
        $this->rows[] = ['name' => $this->name, 'purpose' => $this->purpose, 'time' => now()->format('H:i')];
        $this->name = '';
        $this->purpose = '';
        session()->flash('status', 'Visitor logged');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Visitor Records</h2>

    <form class="grid md:grid-cols-3 gap-4" wire:submit="log">
        <flux:input wire:model="name" label="Name" required />
        <flux:input wire:model="purpose" label="Purpose" />
        <div class="flex items-end">
            <flux:button type="submit" variant="primary">Log</flux:button>
        </div>
    </form>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Name</th>
                    <th class="p-2">Purpose</th>
                    <th class="p-2">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $r['name'] }}</td>
                        <td class="p-2">{{ $r['purpose'] }}</td>
                        <td class="p-2">{{ $r['time'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="3">No visitor logs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


