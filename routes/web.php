<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Role-based dashboards
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Dashboard
    Route::view('/admin/dashboard', 'dashboard')
        ->middleware('role:super_admin,admin')
        ->name('admin.dashboard');
        
    // Coordinator Dashboard
    Route::view('/coordinator/dashboard', 'dashboard')
        ->middleware('role:programme_coordinator')
        ->name('coordinator.dashboard');
        
    // Accounts Dashboard
    Route::view('/accounts/dashboard', 'dashboard')
        ->middleware('role:accounts')
        ->name('accounts.dashboard');
        
    // Lecturer Dashboard
    Route::view('/lecturer/dashboard', 'dashboard')
        ->middleware('role:lecturer')
        ->name('lecturer.dashboard');
    
    // Student Dashboard
    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
        
        // Finances
        Route::get('/finances', [StudentController::class, 'finances'])->name('finances');
        
        // Courses
        Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
        Route::post('/courses/register', [StudentController::class, 'registerCourses'])->name('courses.register');
        
        // Accommodation
        Route::get('/accommodation', [StudentController::class, 'accommodation'])->name('accommodation');
        Route::post('/accommodation/apply', [StudentController::class, 'applyAccommodation'])->name('accommodation.apply');
        
        // Results and academic records
        Route::get('/results', [StudentController::class, 'results'])->name('results');
        
        // Document docket and downloads
        Route::prefix('docket')->name('docket.')->group(function () {
            Route::get('/', [StudentController::class, 'docket'])->name('index');
            Route::get('/download/{type}', [StudentController::class, 'downloadDocket'])->name('download');
            Route::post('/request', [StudentController::class, 'requestDocument'])->name('request');
        });
        
        // Additional Student Routes
        Route::get('/timetable', [StudentController::class, 'timetable'])->name('timetable');
        Route::get('/library', [StudentController::class, 'library'])->name('library');
        Route::get('/notices', [StudentController::class, 'notices'])->name('notices');
        Route::get('/support', [StudentController::class, 'support'])->name('support');
    });
});

// User Settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')
        ->name('settings.profile');
    Volt::route('settings/password', 'settings.password')
        ->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')
        ->name('settings.appearance');
});

// Admin CRUD routes
Route::middleware(['auth','verified','role:super_admin,admin'])->group(function () {
    Volt::route('admin', 'admin.dashboard')
        ->name('admin.home');
    Volt::route('admin/departments', 'admin.departments')
        ->name('admin.departments');
    Volt::route('admin/schools', 'admin.schools')
        ->name('admin.schools');
    Volt::route('admin/programmes', 'admin.programmes')
        ->name('admin.programmes');
    Volt::route('admin/courses', 'admin.courses')
        ->name('admin.courses');
    Volt::route('admin/users', 'admin.users')
        ->name('admin.users');
    Volt::route('admin/roles', 'admin.roles')
        ->name('admin.roles');
    Volt::route('admin/settings', 'admin.settings')
        ->name('admin.settings');
});

require __DIR__.'/auth.php';
