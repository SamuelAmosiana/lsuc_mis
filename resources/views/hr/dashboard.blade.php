@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">HR Dashboard</h1>
        <div class="text-sm text-gray-600">
            Welcome, {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Staff</h3>
            <p class="text-3xl font-bold text-blue-600">{{ \App\Models\User::role('staff')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Present Today</h3>
            <p class="text-3xl font-bold text-green-600">{{ \App\Models\Attendance::whereDate('date', today())->distinct('user_id')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Pending Leave</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ \App\Models\LeaveRequest::where('status', 'pending')->count() }}</p>
        </div>
    </div>

    <!-- HR Operations -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">HR Operations</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Track Staff Attendance -->
            <a href="{{ route('hr.attendance.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-4">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800">Track Staff Attendance</h3>
                        <p class="text-sm text-gray-600">View and manage staff attendance</p>
                    </div>
                </div>
            </a>
            
            <!-- Manage Salaries -->
            <a href="{{ route('hr.salaries.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800">Manage Salaries</h3>
                        <p class="text-sm text-gray-600">Process staff payroll</p>
                    </div>
                </div>
            </a>
            
            <!-- Student Records -->
            <a href="{{ route('hr.students.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg mr-4">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800">Student Records</h3>
                        <p class="text-sm text-gray-600">Review student information</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
