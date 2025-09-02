<?php

use App\Models\StudentMark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $rows;

    public function mount(): void
    {
        $this->rows = StudentMark::where('student_id', auth()->id())->orderByDesc('updated_at')->get();
    }
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Results</h2>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Course</th>
                    <th class="p-2">CA</th>
                    <th class="p-2">Exam</th>
                    <th class="p-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $r->course_id }}</td>
                        <td class="p-2">{{ $r->ca_score }}</td>
                        <td class="p-2">{{ $r->exam_score }}</td>
                        <td class="p-2">{{ $r->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


