<?php

use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $roles;
    public ?int $editingId = null;

    #[Validate('required|string|max:100')]
    public string $role_name = '';

    #[Validate('nullable|string|max:500')]
    public string $role_description = '';

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->roles = Role::orderBy('role_name')->get();
    }

    public function startCreate(): void
    {
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        Role::create(['role_name' => $this->role_name, 'role_description' => $this->role_description ?: null]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Role created');
    }

    public function edit(int $id): void
    {
        $r = Role::findOrFail($id);
        $this->editingId = $r->role_id;
        $this->role_name = $r->role_name;
        $this->role_description = $r->role_description ?? '';
    }

    public function update(): void
    {
        $this->validate();
        $r = Role::findOrFail($this->editingId);
        $r->update(['role_name' => $this->role_name, 'role_description' => $this->role_description ?: null]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Role updated');
    }

    public function delete(int $id): void
    {
        Role::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Role deleted');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->role_name = '';
        $this->role_description = '';
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Roles</h2>
        <flux:button wire:click="startCreate" variant="outline">New Role</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="role_name" label="Name" required />
            <flux:textarea wire:model="role_description" label="Description" />
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
                        <th class="p-2">Description</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $r)
                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                            <td class="p-2">{{ $r->role_id }}</td>
                            <td class="p-2">{{ $r->role_name }}</td>
                            <td class="p-2">{{ $r->role_description }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="outline" wire:click="edit({{ $r->role_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $r->role_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


