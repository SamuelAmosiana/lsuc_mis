<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $rows = [];
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Attendance</h2>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Staff</th>
                    <th class="p-2">Date</th>
                    <th class="p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $r['staff'] }}</td>
                        <td class="p-2">{{ $r['date'] }}</td>
                        <td class="p-2">{{ $r['status'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="3">No attendance records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


