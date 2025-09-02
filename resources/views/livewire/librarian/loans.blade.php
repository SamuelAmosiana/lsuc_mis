<?php

use App\Models\LibraryItem;
use App\Models\LibraryLoan;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $items;
    public $students;
    public $rows;

    #[Validate('required|integer|exists:library_items,id')]
    public $library_item_id = '';

    #[Validate('required|integer|exists:student,student_id')]
    public $student_id = '';

    public string $borrowed_at = '';
    public string $due_at = '';

    public function mount(): void
    {
        $this->items = LibraryItem::orderBy('title')->get();
        $this->students = Student::orderBy('name')->get();
        $this->rows = LibraryLoan::with('item','user')->orderByDesc('borrowed_at')->limit(50)->get();
    }

    public function borrow(): void
    {
        $this->validate();
        LibraryLoan::create([
            'library_item_id' => $this->library_item_id,
            'user_id' => null,
            'student_id' => $this->student_id,
            'borrowed_at' => $this->borrowed_at ?: now()->toDateString(),
            'due_at' => $this->due_at ?: now()->addDays(14)->toDateString(),
        ]);
        $this->rows = LibraryLoan::with('item','user')->orderByDesc('borrowed_at')->limit(50)->get();
        session()->flash('status', 'Loan recorded');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <h2 class="text-2xl font-semibold">Loans</h2>

    <form class="grid md:grid-cols-4 gap-4" wire:submit="borrow">
        <flux:select wire:model="library_item_id" label="Item" required>
            <option value="">-- Select Item --</option>
            @foreach($items as $i)
                <option value="{{ $i->id }}">{{ $i->title }}</option>
            @endforeach
        </flux:select>
        <flux:select wire:model="student_id" label="Student" required>
            <option value="">-- Select Student --</option>
            @foreach($students as $s)
                <option value="{{ $s->student_id }}">{{ $s->name }}</option>
            @endforeach
        </flux:select>
        <flux:input wire:model="borrowed_at" label="Borrowed" type="date" />
        <flux:input wire:model="due_at" label="Due" type="date" />
        <div class="md:col-span-4 flex">
            <flux:button type="submit" variant="primary">Record Loan</flux:button>
        </div>
    </form>

    <div class="rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="p-2">Item</th>
                    <th class="p-2">Student</th>
                    <th class="p-2">Borrowed</th>
                    <th class="p-2">Due</th>
                    <th class="p-2">Returned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr class="border-t border-neutral-200">
                        <td class="p-2">{{ $r->item->title ?? '-' }}</td>
                        <td class="p-2">{{ $r->user->name ?? $r->student_id }}</td>
                        <td class="p-2">{{ $r->borrowed_at }}</td>
                        <td class="p-2">{{ $r->due_at }}</td>
                        <td class="p-2">{{ $r->returned_at ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


