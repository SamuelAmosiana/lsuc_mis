<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Accounts\AccountsController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Role-based dashboards
// Test route for logging - no auth required
Route::get('/test-logger', function () {
    \Log::info('Test log message from /test-logger route');
    return response()->json([
        'message' => 'Log entry created',
        'log_path' => storage_path('logs/laravel.log')
    ]);
});

// Test lecturer route - requires auth and lecturer role
Route::get('/test-lecturer-route', function () {
    $user = auth()->user();
    
    // Get assigned courses
    $assignedCourses = $user->courses()->with('program')->get();
    
    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'assigned_courses' => $assignedCourses,
        'is_lecturer' => $user->role === 'lecturer',
        'is_authenticated' => auth()->check(),
    ]);
})->middleware(['auth', 'role:lecturer']);

Route::middleware(['auth', 'verified'])->group(function () {
    // HR Dashboard (Volt pages)
    Route::prefix('hr')->name('hr.')->middleware('role:human_resource')->group(function () {
        Volt::route('/dashboard', 'hr.dashboard')->name('dashboard');
        Volt::route('/attendance', 'hr.attendance')->name('attendance');
        Volt::route('/salaries', 'hr.salaries')->name('salaries');
        Volt::route('/staff', 'hr.staff')->name('staff');
    });
    
    // Admin Dashboard
    Route::prefix('admin')->name('admin.')->middleware('role:super_admin,admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'userManagement'])->name('users.management');
        Route::get('/reports', [\App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('reports');
    });
        
    // Coordinator (Volt pages)
    Route::prefix('coordinator')->name('coordinator.')->middleware('role:programme_coordinator')->group(function () {
        Volt::route('/dashboard', 'coordinator.dashboard')->name('dashboard');
        Volt::route('/academic-calendar', 'coordinator.calendar')->name('calendar');
        Volt::route('/timetables', 'coordinator.timetables')->name('timetables');
        Volt::route('/registrations', 'coordinator.registrations')->name('registrations');
    });
        
    // Accounts (Volt pages)
    Route::prefix('accounts')->name('accounts.')->middleware('role:accounts')->group(function () {
        Volt::route('/dashboard', 'accounts.dashboard')->name('dashboard');
        Volt::route('/fees', 'accounts.fees')->name('fees');
        Volt::route('/income', 'accounts.income')->name('income');
        Volt::route('/expenses', 'accounts.expenses')->name('expenses');
        Volt::route('/reports', 'accounts.reports')->name('reports');
    });
    
    // Student Dashboard
    // Lecturer Dashboard (Volt pages)
    Route::prefix('lecturer')->name('lecturer.')->middleware('role:lecturer')->group(function () {
        Volt::route('/dashboard', 'lecturer.dashboard')->name('dashboard');
        Volt::route('/upload-marks', 'lecturer.upload-marks')->name('upload-marks');
        Volt::route('/roster', 'lecturer.roster')->name('roster');
        Volt::route('/reports', 'lecturer.reports')->name('reports');
    });
    
    // Student Routes
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
    
    // Enrollment Office (Volt pages)
    Route::prefix('enrollment')->name('enrollment.')->middleware('role:enrollment_officer')->group(function () {
        Volt::route('/dashboard', 'enrollment.dashboard')->name('dashboard');
        Volt::route('/applications', 'enrollment.applications')->name('applications');
        Volt::route('/accommodation', 'enrollment.accommodation')->name('accommodation');
        Volt::route('/communications', 'enrollment.communications')->name('communications');
    });

    // Front Desk (Volt pages)
    Route::prefix('frontdesk')->name('frontdesk.')->middleware('role:front_desk_officer')->group(function () {
        Volt::route('/dashboard', 'frontdesk.dashboard')->name('dashboard');
        Volt::route('/visitors', 'frontdesk.visitors')->name('visitors');
        Volt::route('/meetings', 'frontdesk.meetings')->name('meetings');
        Volt::route('/feedback', 'frontdesk.feedback')->name('feedback');
        Volt::route('/support', 'frontdesk.support')->name('support');
    });

    // Librarian (Volt pages)
    Route::prefix('library')->name('librarian.')->middleware('role:librarian')->group(function () {
        Volt::route('/dashboard', 'librarian.dashboard')->name('dashboard');
        Volt::route('/inventory', 'librarian.inventory')->name('inventory');
        Volt::route('/loans', 'librarian.loans')->name('loans');
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
