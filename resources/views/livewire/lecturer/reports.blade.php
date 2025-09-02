<?php

use App\Models\CourseLSC as Course;
use App\Models\StudentMark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $courses;
    public $course_id = '';
    public $rows;

    public function mount(): void
    {
        $this->courses = Course::orderBy('name')->get();
        $this->rows = collect();
    }

    public function updatedCourseId(): void
    {
        $this->rows = StudentMark::where('course_id', $this->course_id)->orderBy('student_id')->get();
    }
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Performance Reports</h2>

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
                        <th class="p-2">Student ID</th>
                        <th class="p-2">CA</th>
                        <th class="p-2">Exam</th>
                        <th class="p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                        <tr class="border-t border-neutral-200">
                            <td class="p-2">{{ $r->student_id }}</td>
                            <td class="p-2">{{ $r->ca_score }}</td>
                            <td class="p-2">{{ $r->exam_score }}</td>
                            <td class="p-2">{{ $r->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


