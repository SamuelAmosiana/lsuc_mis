<?php

use App\Models\AccommodationAssignment;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $assignments;

    public function mount(): void
    {
        $this->assignments = AccommodationAssignment::where('student_id', auth()->id())->latest()->get();
    }
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Accommodation</h2>
    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5">
        @if($assignments->isEmpty())
            <p class="text-sm text-neutral-700">No accommodation assigned. Apply via Enrollment Office.</p>
        @else
            <ul class="text-sm text-neutral-700 space-y-1">
                @foreach($assignments as $a)
                    <li>Term {{ $a->term }} {{ $a->year }} - Room {{ optional($a->room)->room_number }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>


