<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public string $site_name = 'Lusaka South College MIS';
    public bool $registration_open = true;
    public bool $maintenance_mode = false;

    public function save(): void
    {
        // Stub for persisting settings (e.g., to a settings table)
        session()->flash('status', 'Settings saved');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-xl font-semibold">System Settings</h2>

    <form class="max-w-xl grid gap-4" wire:submit="save">
        <flux:input wire:model="site_name" label="Site Name" />
        <flux:toggle wire:model="registration_open" label="Allow Student Registration" />
        <flux:toggle wire:model="maintenance_mode" label="Maintenance Mode" />
        <flux:button type="submit" variant="primary">Save Settings</flux:button>
    </form>
</div>


