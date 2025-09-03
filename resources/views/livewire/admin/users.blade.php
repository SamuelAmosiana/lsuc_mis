<?php

use App\Models\User;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $users;
    public $roles;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('nullable|string|min:6')]
    public string $password = '';

    #[Validate('required|string')]
    public string $role = 'student';

    public function mount(): void
    {
        $list = Role::orderBy('role_name')->pluck('role_name')->all();
        if (empty($list)) {
            $list = [
                'super_admin','admin','programme_coordinator','human_resource','enrollment_officer','accounts','front_desk_officer','librarian','lecturer','student',
            ];
        }
        $this->roles = $list;
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->users = User::orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ?: 'password',
            'role' => $this->role,
            'email_verified_at' => now(),
        ]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'User created');
    }

    public function edit(int $id): void
    {
        $u = User::findOrFail($id);
        $this->editingId = $u->id;
        $this->name = $u->name;
        $this->email = $u->email;
        $this->role = $u->role;
    }

    public function update(): void
    {
        $this->validate();
        $u = User::findOrFail($this->editingId);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
        if ($this->password !== '') {
            $data['password'] = $this->password;
        }
        $u->update($data);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'User updated');
    }

    public function delete(int $id): void
    {
        User::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'User deleted');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'student';
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Users</h2>
        <flux:button wire:click="startCreate" variant="outline">New User</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="name" label="Name" required />
            <flux:input wire:model="email" label="Email" type="email" required />
            <flux:input wire:model="password" label="Password" type="password" placeholder="Leave blank to keep" />
            <flux:select wire:model="role" label="Role" required>
                @forelse($roles as $r)
                    <option value="{{ $r }}">{{ ucfirst(str_replace('_',' ', $r)) }}</option>
                @empty
                    <option value="student">Student</option>
                @endforelse
            </flux:select>
            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }}</flux:button>
                @if($editingId)
                    <flux:button type="button" wire:click="startCreate" variant="ghost">Cancel</flux:button>
                @endif
            </div>
        </form>

        <div class="md:col-span-2 overflow-auto rounded-xl bg-white border border-orange-200 shadow-sm">
            <div class="p-4 border-b border-orange-100 flex items-center justify-between">
                <div class="text-sm text-neutral-700">Total Users: {{ count($users) }}</div>
                <div class="text-xs text-neutral-500">Real-time</div>
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Role</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr class="border-t border-neutral-200 dark:border-neutral-700 hover:bg-orange-50/40">
                            <td class="p-2">{{ $u->id }}</td>
                            <td class="p-2">{{ $u->name }}</td>
                            <td class="p-2">{{ $u->email }}</td>
                            <td class="p-2">{{ ucfirst(str_replace('_',' ', $u->role)) }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="outline" wire:click="edit({{ $u->id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $u->id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


