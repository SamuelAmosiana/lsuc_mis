<?php

use App\Models\Salary;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $salaries;

    public function mount(): void
    {
        $this->salaries = Salary::orderByDesc('date_paid')->limit(25)->get();
    }
}; ?>

<div class="flex flex-col gap-6">
    <h2 class="text-2xl font-semibold">Salaries</h2>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Name</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Date Paid</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $s)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $s->name }}</td>
                        <td class="p-2">{{ number_format($s->amount, 2) }}</td>
                        <td class="p-2">{{ $s->date_paid?->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2 text-neutral-500" colspan="3">No salary records.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


