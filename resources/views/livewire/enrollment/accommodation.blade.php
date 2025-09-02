<?php

use App\Models\AccommodationAssignment;
use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $assignments;
    public $rooms;

    public function mount(): void
    {
        $this->assignments = AccommodationAssignment::with('student','room')->latest()->limit(50)->get();
        $this->rooms = Room::with('hostel')->orderBy('room_number')->get();
    }
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Accommodation</h2>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Student</th>
                    <th class="p-2">Hostel</th>
                    <th class="p-2">Room</th>
                    <th class="p-2">Term</th>
                    <th class="p-2">Year</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $a->student->name ?? '-' }}</td>
                        <td class="p-2">{{ $a->room->hostel->name ?? '-' }}</td>
                        <td class="p-2">{{ $a->room->room_number ?? '-' }}</td>
                        <td class="p-2">{{ $a->term }}</td>
                        <td class="p-2">{{ $a->year }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="5">No assignments yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


