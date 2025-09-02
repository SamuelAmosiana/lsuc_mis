<?php

use App\Models\Student;
use App\Models\CourseLSC as Course;
use App\Models\StudentMark;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $courses;
    public $students;

    #[Validate('required|integer|exists:course,course_id')]
    public $course_id = '';

    #[Validate('nullable|string')]
    public string $term = '';

    #[Validate('nullable|integer|min:2000|max:2100')]
    public $year = '';

    public array $marks = [];

    public function mount(): void
    {
        $this->courses = Course::orderBy('name')->get();
        $this->students = collect();
    }

    public function updatedCourseId($value): void
    {
        $this->students = Student::orderBy('name')->get();
        $this->loadExisting();
    }

    public function loadExisting(): void
    {
        if (! $this->course_id) return;
        $existing = StudentMark::where('course_id', $this->course_id)
            ->where('term', $this->term ?: null)
            ->where('year', $this->year ?: null)
            ->get()
            ->keyBy('student_id');

        foreach ($this->students as $s) {
            $m = $existing->get($s->student_id);
            $this->marks[$s->student_id] = [
                'ca_score' => $m->ca_score ?? null,
                'exam_score' => $m->exam_score ?? null,
            ];
        }
    }

    public function save(): void
    {
        $this->validate();
        foreach ($this->marks as $studentId => $row) {
            $ca = $row['ca_score'] !== null && $row['ca_score'] !== '' ? (float)$row['ca_score'] : null;
            $exam = $row['exam_score'] !== null && $row['exam_score'] !== '' ? (float)$row['exam_score'] : null;
            $total = $ca !== null || $exam !== null ? (float) (($ca ?? 0) + ($exam ?? 0)) : null;

            StudentMark::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $this->course_id,
                    'term' => $this->term ?: null,
                    'year' => $this->year ?: null,
                ],
                [
                    'lecturer_staff_id' => null,
                    'ca_score' => $ca,
                    'exam_score' => $exam,
                    'total' => $total,
                ]
            );
        }

        session()->flash('status', 'Marks saved');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Upload CA & Exam Marks</h2>

    <form class="grid md:grid-cols-4 gap-4" wire:submit="save">
        <flux:select wire:model="course_id" label="Course" required>
            <option value="">-- Select Course --</option>
            @foreach($courses as $c)
                <option value="{{ $c->course_id }}">{{ $c->name }}</option>
            @endforeach
        </flux:select>
        <flux:input wire:model="term" label="Term" placeholder="e.g. Jan/Jun" />
        <flux:input wire:model="year" label="Year" type="number" min="2000" max="2100" />
        <div class="flex items-end">
            <flux:button type="submit" variant="primary">Save Marks</flux:button>
        </div>
    </form>

    @if($course_id)
        <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Student</th>
                        <th class="p-2">CA</th>
                        <th class="p-2">Exam</th>
                        <th class="p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $s)
                        <?php $ca = $marks[$s->student_id]['ca_score'] ?? null; $ex = $marks[$s->student_id]['exam_score'] ?? null; ?>
                        <tr class="border-t border-neutral-200">
                            <td class="p-2 w-64">{{ $s->name }}</td>
                            <td class="p-2 w-40"><flux:input wire:model.defer="marks.{{ $s->student_id }}.ca_score" type="number" step="0.01" /></td>
                            <td class="p-2 w-40"><flux:input wire:model.defer="marks.{{ $s->student_id }}.exam_score" type="number" step="0.01" /></td>
                            <td class="p-2 w-40">{{ ($ca ?? 0) + ($ex ?? 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


