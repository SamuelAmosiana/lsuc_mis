@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Examination Results</h1>
        <div class="mt-4 md:mt-0 space-x-3">
            <select class="w-40 p-2 border rounded">
                <option>All Programs</option>
                @foreach($courses->unique('programme_id') as $course)
                    @if($course->programme)
                        <option value="{{ $course->programme_id }}">{{ $course->programme->programme_name }}</option>
                    @endif
                @endforeach
            </select>
            <select class="w-32 p-2 border rounded">
                <option>All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->course_code }}</option>
                @endforeach
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">
                Filter
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg mb-8">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium">Results Summary</h3>
                    <p class="text-sm text-gray-500">View and manage examination results</p>
                </div>
                <div class="space-x-2">
                    <button class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                        Download
                    </button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                        Upload
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $results = [
                            ['name' => 'John Doe', 'course' => 'CS101 - Intro to CS', 'score' => 85, 'grade' => 'A', 'status' => 'Published'],
                            ['name' => 'Jane Smith', 'course' => 'CS201 - Database Systems', 'score' => 78, 'grade' => 'B+', 'status' => 'Published'],
                            ['name' => 'Robert Johnson', 'course' => 'CS301 - Web Dev', 'score' => 92, 'grade' => 'A+', 'status' => 'Published'],
                            ['name' => 'Emily Davis', 'course' => 'CS202 - Data Structures', 'score' => 65, 'grade' => 'C+', 'status' => 'Draft'],
                        ];
                    @endphp
                    
                    @foreach($results as $result)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                    {{ strtoupper(substr($result['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ $result['name'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $result['course'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $result['score'] }}%
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $result['grade'] === 'A' || $result['grade'] === 'A+' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $result['grade'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $result['status'] === 'Published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $result['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-t flex justify-between items-center">
            <div class="text-sm text-gray-700">
                Showing 1 to 4 of 4 entries
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 border rounded">Previous</button>
                <button class="px-3 py-1 border rounded bg-blue-600 text-white">1</button>
                <button class="px-3 py-1 border rounded">2</button>
                <button class="px-3 py-1 border rounded">Next</button>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cas-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Results</p>
                    <p class="text-2xl font-semibold">1,245</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pass Rate</p>
                    <p class="text-2xl font-semibold">87%</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Average Score</p>
                    <p class="text-2xl font-semibold">76.5%</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
