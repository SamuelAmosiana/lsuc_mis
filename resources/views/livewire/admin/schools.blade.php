<?php

use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $schools;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->schools = School::orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        School::create(['name' => $this->name]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'School created');
    }

    public function edit(int $id): void
    {
        $s = School::findOrFail($id);
        $this->editingId = $s->school_id;
        $this->name = $s->name;
    }

    public function update(): void
    {
        $this->validate();
        $s = School::findOrFail($this->editingId);
        $s->update(['name' => $this->name]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'School updated');
    }

    public function delete(int $id): void
    {
        School::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'School deleted');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Schools</h2>
        <flux:button wire:click="startCreate" variant="secondary">New School</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="name" label="Name" required />
            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }}</flux:button>
                @if($editingId)
                    <flux:button type="button" wire:click="startCreate" variant="ghost">Cancel</flux:button>
                @endif
            </div>
        </form>

        <div class="md:col-span-2 overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $s)
                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                            <td class="p-2">{{ $s->school_id }}</td>
                            <td class="p-2">{{ $s->name }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="secondary" wire:click="edit({{ $s->school_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $s->school_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


