<?php

use App\Models\Programme;
use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $programmes;
    public $schools;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|integer|exists:school,school_id')]
    public $school_id = '';

    public function mount(): void
    {
        $this->schools = School::orderBy('name')->get();
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->programmes = Programme::with('school')->orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        Programme::create(['name' => $this->name, 'school_id' => $this->school_id ?: null]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Programme created');
    }

    public function edit(int $id): void
    {
        $p = Programme::findOrFail($id);
        $this->editingId = $p->programme_id;
        $this->name = $p->name;
        $this->school_id = $p->school_id;
    }

    public function update(): void
    {
        $this->validate();
        $p = Programme::findOrFail($this->editingId);
        $p->update(['name' => $this->name, 'school_id' => $this->school_id ?: null]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Programme updated');
    }

    public function delete(int $id): void
    {
        Programme::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Programme deleted');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->school_id = '';
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Programmes</h2>
        <flux:button wire:click="startCreate" variant="secondary">New Programme</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="name" label="Name" required />
            <flux:select wire:model="school_id" label="School">
                <option value="">-- None --</option>
                @foreach($schools as $s)
                    <option value="{{ $s->school_id }}">{{ $s->name }}</option>
                @endforeach
            </flux:select>
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
                        <th class="p-2">School</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programmes as $p)
                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                            <td class="p-2">{{ $p->programme_id }}</td>
                            <td class="p-2">{{ $p->name }}</td>
                            <td class="p-2">{{ $p->school->name ?? '-' }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="secondary" wire:click="edit({{ $p->programme_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $p->programme_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


