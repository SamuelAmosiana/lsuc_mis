<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $rows = [];

    #[Validate('required|string|min:3')]
    public string $message = '';

    public function save(): void
    {
        $this->validate();
        $this->rows[] = ['message' => $this->message, 'time' => now()->format('Y-m-d H:i')];
        $this->message = '';
        session()->flash('status', 'Feedback captured');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Client Feedback</h2>

    <form class="grid gap-4 max-w-2xl" wire:submit="save">
        <flux:textarea wire:model="message" label="Feedback" placeholder="Type client feedback or complaint..." />
        <div>
            <flux:button type="submit" variant="primary">Save</flux:button>
        </div>
    </form>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Feedback</th>
                    <th class="p-2">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $r['message'] }}</td>
                        <td class="p-2">{{ $r['time'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="2">No feedback yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


