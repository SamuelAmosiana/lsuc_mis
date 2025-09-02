<?php

use App\Models\Fee;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $rows;
    public ?int $editingId = null;

    #[Validate('required|string|max:100')]
    public string $name = '';

    #[Validate('required|numeric|min:0')]
    public $amount = '';

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->rows = Fee::orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->amount = '';
    }

    public function create(): void
    {
        $this->validate();
        Fee::create(['name' => $this->name, 'amount' => $this->amount]);
        $this->startCreate();
        $this->loadData();
        session()->flash('status', 'Fee created');
    }

    public function edit(int $id): void
    {
        $f = Fee::findOrFail($id);
        $this->editingId = $f->fee_id;
        $this->name = $f->name;
        $this->amount = $f->amount;
    }

    public function update(): void
    {
        $this->validate();
        $f = Fee::findOrFail($this->editingId);
        $f->update(['name' => $this->name, 'amount' => $this->amount]);
        $this->startCreate();
        $this->loadData();
        session()->flash('status', 'Fee updated');
    }

    public function delete(int $id): void
    {
        Fee::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Fee deleted');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Fees</h2>
        <flux:button wire:click="startCreate" variant="outline">New Fee</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="name" label="Name" required />
            <flux:input wire:model="amount" label="Amount" type="number" step="0.01" required />
            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }}</flux:button>
                @if($editingId)
                    <flux:button type="button" wire:click="startCreate" variant="ghost">Cancel</flux:button>
                @endif
            </div>
        </form>

        <div class="md:col-span-2 rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Name</th>
                        <th class="p-2">Amount</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                        <tr class="border-t border-neutral-200">
                            <td class="p-2">{{ $r->name }}</td>
                            <td class="p-2">{{ number_format($r->amount, 2) }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="outline" wire:click="edit({{ $r->fee_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $r->fee_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


