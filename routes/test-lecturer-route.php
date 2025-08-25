<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

Route::get('/test-lecturer-route', function () {
    // Test logging
    Log::info('Test log message from test-lecturer-route');
    
    // Test authentication
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }
    
    // Test lecturer role
    if (Auth::user()->role !== 'lecturer') {
        return response()->json(['error' => 'Not authorized - lecturer role required'], 403);
    }
    
    // Test lecturer dashboard data
    $lecturer = Auth::user();
    $assignedCourses = $lecturer->courses()->count();
    
    return response()->json([
        'message' => 'Test route working',
        'user' => [
            'id' => $lecturer->id,
            'name' => $lecturer->name,
            'email' => $lecturer->email,
            'role' => $lecturer->role,
        ],
        'assigned_courses_count' => $assignedCourses,
    ]);
})->middleware(['auth', 'role:lecturer']);
