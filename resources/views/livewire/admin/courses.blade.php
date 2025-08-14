<?php

use App\Models\CourseLSC as Course;
use App\Models\Programme;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $courses;
    public $programmes;
    public ?int $editingId = null;

    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|integer|exists:programme,programme_id')]
    public $programme_id = '';

    public function mount(): void
    {
        $this->programmes = Programme::orderBy('name')->get();
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->courses = Course::with('programme')->orderBy('name')->get();
    }

    public function startCreate(): void
    {
        $this->resetForm();
    }

    public function create(): void
    {
        $this->validate();
        Course::create(['name' => $this->name, 'programme_id' => $this->programme_id ?: null]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Course created');
    }

    public function edit(int $id): void
    {
        $c = Course::findOrFail($id);
        $this->editingId = $c->course_id;
        $this->name = $c->name;
        $this->programme_id = $c->programme_id;
    }

    public function update(): void
    {
        $this->validate();
        $c = Course::findOrFail($this->editingId);
        $c->update(['name' => $this->name, 'programme_id' => $this->programme_id ?: null]);
        $this->resetForm();
        $this->loadData();
        session()->flash('status', 'Course updated');
    }

    public function delete(int $id): void
    {
        Course::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Course deleted');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->programme_id = '';
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">Courses</h2>
        <flux:button wire:click="startCreate" variant="secondary">New Course</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="name" label="Name" required />
            <flux:select wire:model="programme_id" label="Programme">
                <option value="">-- None --</option>
                @foreach($programmes as $p)
                    <option value="{{ $p->programme_id }}">{{ $p->name }}</option>
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
                        <th class="p-2">Programme</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $c)
                        <tr class="border-t border-neutral-200 dark:border-neutral-700">
                            <td class="p-2">{{ $c->course_id }}</td>
                            <td class="p-2">{{ $c->name }}</td>
                            <td class="p-2">{{ $c->programme->name ?? '-' }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="secondary" wire:click="edit({{ $c->course_id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $c->course_id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


