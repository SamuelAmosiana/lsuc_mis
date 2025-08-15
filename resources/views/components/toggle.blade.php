<div wire:ignore>
    <label class="flex items-center cursor-pointer">
        <div class="relative">
            <input 
                type="checkbox" 
                @if($active) wire:model="{{ $active }}" @endif
                class="sr-only"
            >
            <div class="block bg-gray-300 w-12 h-6 rounded-full"></div>
            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"></div>
        </div>
        @if($label)
            <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
        @endif
    </label>
</div>

<style>
    input:checked ~ .block {
        background-color: #6366f1;
    }
    input:checked ~ .dot {
        transform: translateX(150%);
    }
</style>