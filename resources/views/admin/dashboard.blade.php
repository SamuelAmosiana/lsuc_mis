@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
        <div class="text-sm text-gray-600">
            Welcome, {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Users</h3>
            <p class="text-3xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Sessions</h3>
            <p class="text-3xl font-bold text-green-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">System Status</h3>
            <div class="flex items-center">
                <span class="h-3 w-3 bg-green-500 rounded-full mr-2"></span>
                <span class="text-green-600 font-medium">Operational</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.management') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                <h3 class="font-medium text-gray-800">Manage Users</h3>
                <p class="text-sm text-gray-600">Add, edit, or remove system users</p>
            </a>
            <a href="{{ route('admin.reports') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                <h3 class="font-medium text-gray-800">View Reports</h3>
                <p class="text-sm text-gray-600">Generate and view system reports</p>
            </a>
            <a href="#" class="block p-4 border rounded-lg hover:bg-gray-50 transition">
                <h3 class="font-medium text-gray-800">System Settings</h3>
                <p class="text-sm text-gray-600">Configure system preferences</p>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
        <div class="space-y-4">
            <div class="flex items-start pb-4 border-b">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">New user registered</p>
                    <p class="text-xs text-gray-500">2 minutes ago</p>
                </div>
            </div>
            <!-- More activity items would go here -->
        </div>
    </div>
</div>
@endsection
