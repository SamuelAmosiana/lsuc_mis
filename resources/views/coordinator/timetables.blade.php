@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Timetable Management</h1>
        <div class="mt-4 md:mt-0">
            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Generate Timetable
            </button>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Academic Timetable
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Manage and view class schedules
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <div class="flex rounded-md shadow-sm">
                        <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option>All Programmes</option>
                            @foreach(\App\Models\Programme::all() as $programme)
                                <option value="{{ $programme->id }}">{{ $programme->programme_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Monday
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tuesday
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wednesday
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thursday
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Friday
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $timeSlots = [
                            ['start' => '08:00', 'end' => '09:30'],
                            ['start' => '09:30', 'end' => '11:00'],
                            ['start' => '11:00', 'end' => '12:30'],
                            ['start' => '12:30', 'end' => '14:00'], // Lunch
                            ['start' => '14:00', 'end' => '15:30'],
                            ['start' => '15:30', 'end' => '17:00'],
                        ];
                        
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                    @endphp
                    
                    @foreach($timeSlots as $timeSlot)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $timeSlot['start'] }} - {{ $timeSlot['end'] }}
                        </td>
                        @foreach($days as $day)
                            @php
                                $classes = $timetables->filter(function($item) use ($day, $timeSlot) {
                                    return $item->day_of_week === $day && 
                                           $item->start_time <= $timeSlot['end'] && 
                                           $item->end_time >= $timeSlot['start'];
                                });
                            @endphp
                            <td class="px-6 py-4">
                                @foreach($classes as $class)
                                    <div class="mb-2 p-2 bg-blue-50 rounded border-l-4 border-blue-500">
                                        <div class="text-sm font-medium text-gray-900">{{ $class->course->course_code }}</div>
                                        <div class="text-xs text-gray-500">{{ $class->lecturer->first_name ?? 'TBA' }}</div>
                                        <div class="text-xs text-gray-500">{{ $class->room->room_name ?? 'TBA' }}</div>
                                    </div>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
