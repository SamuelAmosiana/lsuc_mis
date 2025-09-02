<?php

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    #[Validate('required|string|max:150')]
    public string $name = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:255')]
    public string $address = '';

    public function mount(): void
    {
        $student = Student::where('student_id', auth()->id())->first();
        if ($student) {
            $this->name = $student->name;
            $this->phone = (string) ($student->phone ?? '');
            $this->address = (string) ($student->address ?? '');
        } else {
            $this->name = auth()->user()->name;
        }
    }

    public function save(): void
    {
        $this->validate();
        Student::updateOrCreate(
            ['student_id' => auth()->id()],
            ['name' => $this->name, 'phone' => $this->phone ?: null, 'address' => $this->address ?: null]
        );
        session()->flash('status', 'Profile saved');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Profile</h2>

    <form class="max-w-xl grid gap-4" wire:submit="save">
        <flux:input wire:model="name" label="Full Name" required />
        <flux:input wire:model="phone" label="Phone" />
        <flux:textarea wire:model="address" label="Address" />
        <flux:button type="submit" variant="primary">Save</flux:button>
    </form>
</div>


