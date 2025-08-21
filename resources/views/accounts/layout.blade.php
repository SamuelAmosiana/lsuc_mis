@extends('components.layouts.app')

@section('content')
    <div class="flex flex-col">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $title ?? 'Accounts' }}
            </h1>
            
            @isset($header)
                <div class="flex items-center space-x-2">
                    {{ $header }}
                </div>
            @endisset
        </div>

        <!-- Page Content -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm">
            @if(isset($tabs))
                <div class="border-b border-gray-200 dark:border-zinc-700">
                    <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                        {{ $tabs }}
                    </nav>
                </div>
            @endif
            
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
@endsection
