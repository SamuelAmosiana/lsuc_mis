@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Reports</h1>
        <div class="flex space-x-3">
            <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export
            </button>
            <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
                Filter
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- User Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">User Statistics</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Today
                </span>
            </div>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Total Users</span>
                        <span class="font-medium">{{ \App\Models\User::count() }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Active Today</span>
                        <span class="font-medium">24</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>New This Week</span>
                        <span class="font-medium">12</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">System Health</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Server Uptime</span>
                        <span class="font-medium">99.9%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 99.9%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Storage Usage</span>
                        <span class="font-medium">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Response Time</span>
                        <span class="font-medium">128ms</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stats</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-blue-50 rounded-lg text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Student::count() }}</p>
                    <p class="text-sm text-gray-600">Students</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg text-center">
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Staff::where('staff_type', 'lecturer')->count() }}</p>
                    <p class="text-sm text-gray-600">Lecturers</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\CourseLSC::count() }}</p>
                    <p class="text-sm text-gray-600">Courses</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Programme::count() }}</p>
                    <p class="text-sm text-gray-600">Programs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach([
                ['user' => 'John Doe', 'action' => 'created a new course', 'time' => '2 minutes ago', 'icon' => 'plus-circle', 'color' => 'text-green-500'],
                ['user' => 'Jane Smith', 'action' => 'updated user permissions', 'time' => '10 minutes ago', 'icon' => 'key', 'color' => 'text-blue-500'],
                ['user' => 'System', 'action' => 'completed nightly backup', 'time' => '2 hours ago', 'icon' => 'database', 'color' => 'text-purple-500'],
                ['user' => 'Admin', 'action' => 'generated system report', 'time' => '5 hours ago', 'icon' => 'document-report', 'color' => 'text-yellow-500'],
                ['user' => 'System', 'action' => 'applied security patches', 'time' => '1 day ago', 'icon' => 'shield-check', 'color' => 'text-red-500'],
            ] as $activity)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 {{ $activity['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $activity['user'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                        <p class="text-sm text-gray-600">{{ $activity['action'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="bg-gray-50 px-6 py-3 text-right">
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all activities</a>
        </div>
    </div>
</div>
@endsection
