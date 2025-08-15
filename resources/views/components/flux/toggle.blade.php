<label class="inline-flex items-center cursor-pointer">
    <input type="checkbox" 
           wire:model="{{ $attributes->get('wire:model') ?? 'checked' }}" 
           class="sr-only peer">
    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer dark:bg-gray-700 peer-checked:bg-indigo-600 relative transition-colors">
        <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></span>
    </div>
    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
        {{ $slot }}
    </span>
</label>
