<?php

use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $departments;

    public ?int $editingId = null;

    #[Validate('required|string|max:100')]
    public string $name = '';

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->departments = Department::orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        Department::create(['name' => $this->name]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Department created');
    }

    public function edit(int $id): void
    {
        $dep = Department::findOrFail($id);
        $this->editingId = $dep->department_id;
        $this->name = $dep->name;
    }

    public function update(): void
    {
        $this->validate();
        $dep = Department::findOrFail($this->editingId);
        $dep->update(['name' => $this->name]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Department updated');
    }

    public function delete(int $id): void
    {
        Department::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Department deleted');
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
        <h2 class="text-xl font-semibold">Departments</h2>
        <flux:button wire:click="startCreate" variant="secondary">New Department</flux:button>
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
                    @foreach($departments as $dep)
                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                            <td class="p-2">{{ $dep->department_id }}</td>
                            <td class="p-2">{{ $dep->name }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="secondary" wire:click="edit({{ $dep->department_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $dep->department_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


