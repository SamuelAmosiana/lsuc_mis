<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-lg font-semibold mb-2">Welcome</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">You are logged in as <strong>{{ auth()->user()->role ?? 'user' }}</strong>.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <flux:button :href="route('admin.departments')" wire:navigate>Manage Departments</flux:button>
                    <flux:button :href="route('admin.schools')" wire:navigate>Manage Schools</flux:button>
                    <flux:button :href="route('admin.programmes')" wire:navigate>Manage Programmes</flux:button>
                    <flux:button :href="route('admin.courses')" wire:navigate>Manage Courses</flux:button>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
