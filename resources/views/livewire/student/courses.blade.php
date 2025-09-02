<?php

use App\Models\CourseLSC as Course;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $courses;
    public array $selected = [];

    public function mount(): void
    {
        $this->courses = Course::orderBy('name')->get();
    }

    public function register(): void
    {
        // Stub: persist to student_course pivot
        session()->flash('status', 'Courses registered');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Register Courses</h2>

    <form class="grid gap-4" wire:submit="register">
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
            <div class="grid md:grid-cols-2 gap-2">
                @foreach($courses as $c)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="selected" value="{{ $c->course_id }}" />
                        <span>{{ $c->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div>
            <flux:button type="submit" variant="primary">Submit</flux:button>
        </div>
    </form>
</div>


