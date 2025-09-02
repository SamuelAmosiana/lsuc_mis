<?php

use App\Models\CourseLSC as Course;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $courses;
    public $students;
    public $course_id = '';

    public function mount(): void
    {
        $this->courses = Course::orderBy('name')->get();
        $this->students = collect();
    }

    public function updatedCourseId(): void
    {
        // In a full implementation, filter by students registered for this course
        $this->students = Student::orderBy('name')->get();
    }
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Course Rosters</h2>

    <div class="grid md:grid-cols-3 gap-4">
        <flux:select wire:model="course_id" label="Course">
            <option value="">-- Select Course --</option>
            @foreach($courses as $c)
                <option value="{{ $c->course_id }}">{{ $c->name }}</option>
            @endforeach
        </flux:select>
    </div>

    @if($course_id)
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Student</th>
                        <th class="p-2">Programme</th>
                        <th class="p-2">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $s)
                        <tr class="border-t border-neutral-200">
                            <td class="p-2">{{ $s->name }}</td>
                            <td class="p-2">{{ optional($s->programme)->name }}</td>
                            <td class="p-2">{{ $s->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


