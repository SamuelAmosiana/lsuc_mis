<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public array $rows = [];
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Applications</h2>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Applicant</th>
                    <th class="p-2">Programme</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $r['name'] ?? '-' }}</td>
                        <td class="p-2">{{ $r['programme'] ?? '-' }}</td>
                        <td class="p-2">{{ $r['status'] ?? 'pending' }}</td>
                        <td class="p-2 flex gap-2">
                            <flux:button size="xs" variant="primary">Approve</flux:button>
                            <flux:button size="xs" variant="danger">Reject</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="4">No applications yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


