<?php

use App\Models\Staff;
use App\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $rows;
    public $departments;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    public $department_id = '';

    public function mount(): void
    {
        $this->departments = Department::orderBy('name')->get();
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->rows = Staff::with('department')->orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->department_id = '';
    }

    public function create(): void
    {
        $this->validate();
        Staff::create(['name' => $this->name, 'email' => $this->email, 'department_id' => $this->department_id ?: null]);
        $this->startCreate();
        $this->loadData();
        session()->flash('status', 'Staff created');
    }

    public function edit(int $id): void
    {
        $s = Staff::findOrFail($id);
        $this->editingId = $s->staff_id;
        $this->name = $s->name;
        $this->email = $s->email;
        $this->department_id = $s->department_id;
    }

    public function update(): void
    {
        $this->validate();
        $s = Staff::findOrFail($this->editingId);
        $s->update(['name' => $this->name, 'email' => $this->email, 'department_id' => $this->department_id ?: null]);
        $this->startCreate();
        $this->loadData();
        session()->flash('status', 'Staff updated');
    }

    public function delete(int $id): void
    {
        Staff::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Staff deleted');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Staff</h2>
        <flux:button wire:click="startCreate" variant="outline">New Staff</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="name" label="Name" required />
            <flux:input wire:model="email" label="Email" type="email" required />
            <flux:select wire:model="department_id" label="Department">
                <option value="">-- None --</option>
                @foreach($departments as $d)
                    <option value="{{ $d->department_id }}">{{ $d->name }}</option>
                @endforeach
            </flux:select>
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
                        <th class="p-2">Email</th>
                        <th class="p-2">Department</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                        <tr class="border-t border-neutral-200">
                            <td class="p-2">{{ $r->name }}</td>
                            <td class="p-2">{{ $r->email }}</td>
                            <td class="p-2">{{ $r->department->name ?? '-' }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="outline" wire:click="edit({{ $r->staff_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $r->staff_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


