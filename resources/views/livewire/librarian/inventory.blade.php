<?php

use App\Models\LibraryItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $rows;
    public ?int $editingId = null;

    #[Validate('nullable|string|max:20')]
    public string $isbn = '';

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:255')]
    public string $author = '';

    public $total_copies = '';
    public $available_copies = '';

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->rows = LibraryItem::orderBy('title')->get();
    }

    public function startCreate(): void
    {
        $this->editingId = null;
        $this->isbn = '';
        $this->title = '';
        $this->author = '';
        $this->total_copies = '';
        $this->available_copies = '';
    }

    public function create(): void
    {
        $this->validate();
        LibraryItem::create([
            'isbn' => $this->isbn ?: null,
            'title' => $this->title,
            'author' => $this->author ?: null,
            'total_copies' => (int) ($this->total_copies ?: 1),
            'available_copies' => (int) ($this->available_copies ?: 1),
        ]);
        $this->startCreate();
        $this->loadData();
        session()->flash('status', 'Item created');
    }

    public function edit(int $id): void
    {
        $i = LibraryItem::findOrFail($id);
        $this->editingId = $i->id;
        $this->isbn = (string) ($i->isbn ?? '');
        $this->title = $i->title;
        $this->author = (string) ($i->author ?? '');
        $this->total_copies = $i->total_copies;
        $this->available_copies = $i->available_copies;
    }

    public function update(): void
    {
        $this->validate();
        $i = LibraryItem::findOrFail($this->editingId);
        $i->update([
            'isbn' => $this->isbn ?: null,
            'title' => $this->title,
            'author' => $this->author ?: null,
            'total_copies' => (int) $this->total_copies,
            'available_copies' => (int) $this->available_copies,
        ]);
        $this->startCreate();
        $this->loadData();
        session()->flash('status', 'Item updated');
    }

    public function delete(int $id): void
    {
        LibraryItem::whereKey($id)->delete();
        $this->loadData();
        session()->flash('status', 'Item deleted');
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-session-status class="text-center" :status="session('status')" />
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Library Inventory</h2>
        <flux:button wire:click="startCreate" variant="outline">New Item</flux:button>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <form class="flex flex-col gap-4" wire:submit="{{ $editingId ? 'update' : 'create' }}">
            <flux:input wire:model="isbn" label="ISBN" />
            <flux:input wire:model="title" label="Title" required />
            <flux:input wire:model="author" label="Author" />
            <flux:input wire:model="total_copies" label="Total Copies" type="number" min="1" />
            <flux:input wire:model="available_copies" label="Available Copies" type="number" min="0" />
            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }}</flux:button>
                @if($editingId)
                    <flux:button type="button" wire:click="startCreate" variant="ghost">Cancel</flux:button>
                @endif
            </div>
        </form>

        <div class="md:col-span-2 rounded-xl bg-white border border-orange-200 shadow-sm p-5 overflow-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Title</th>
                        <th class="p-2">Author</th>
                        <th class="p-2">ISBN</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Available</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                        <tr class="border-t border-neutral-200">
                            <td class="p-2">{{ $r->title }}</td>
                            <td class="p-2">{{ $r->author }}</td>
                            <td class="p-2">{{ $r->isbn }}</td>
                            <td class="p-2">{{ $r->total_copies }}</td>
                            <td class="p-2">{{ $r->available_copies }}</td>
                            <td class="p-2 flex gap-2">
                                <flux:button size="xs" variant="outline" wire:click="edit({{ $r->id }})">Edit</flux:button>
                                <flux:button size="xs" variant="danger" wire:click="delete({{ $r->id }})">Delete</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


