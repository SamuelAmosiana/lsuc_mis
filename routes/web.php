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
    // HR Dashboard
    Route::prefix('hr')->name('hr.')->middleware('role:hr')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\HR\HRController::class, 'dashboard'])->name('dashboard');
        Route::get('/attendance', [\App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/salaries', [\App\Http\Controllers\HR\SalaryController::class, 'index'])->name('salaries.index');
        Route::get('/students', [\App\Http\Controllers\HR\StudentController::class, 'index'])->name('students.index');
    });
    
    // Admin Dashboard
    Route::prefix('admin')->name('admin.')->middleware('role:super_admin,admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'userManagement'])->name('users.management');
        Route::get('/reports', [\App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('reports');
    });
        
    // Coordinator Dashboard
    Route::prefix('coordinator')->name('coordinator.')->middleware('role:programme_coordinator')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'dashboard'])
            ->name('dashboard');
            
        // Academic Calendar
        Route::get('/academic-calendar', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'academicCalendar'])
            ->name('calendar');
            
        // Timetables
        Route::get('/timetables', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'timetables'])
            ->name('timetables');
            
        // Student Registrations
        Route::get('/registrations', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'studentRegistrations'])
            ->name('registrations');
            
        // Approve/Reject Registration
        Route::post('/registrations/{registration}/approve', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'approveRegistration'])
            ->name('registrations.approve');
            
        Route::post('/registrations/{registration}/reject', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'rejectRegistration'])
            ->name('registrations.reject');
            
        // Lecturer Attendance
        Route::get('/attendance', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'lecturerAttendance'])
            ->name('attendance');
            
        // Results
        Route::get('/results', [\App\Http\Controllers\Coordinator\ProgrammesCoordinatorController::class, 'results'])
            ->name('results');
    });
        
    // Accounts Dashboard
    Route::view('/accounts/dashboard', 'dashboard')
        ->middleware('role:accounts')
        ->name('accounts.dashboard');
        
    // Accounts Dashboard
    Route::prefix('accounts')->name('accounts.')->middleware('role:accountant,admin,super_admin')->group(function () {
        Route::get('/dashboard', [AccountsController::class, 'dashboard'])->name('dashboard');
        Route::get('/income', [AccountsController::class, 'income'])->name('income.index');
        Route::get('/expenses', [AccountsController::class, 'expenses'])->name('expenses.index');
        Route::get('/reports', [AccountsController::class, 'reports'])->name('reports.index');
        Route::get('/fees', [AccountsController::class, 'studentFees'])->name('fees.index');
        Route::post('/fees/{fee}/payments', [AccountsController::class, 'recordPayment'])->name('fees.record-payment');
    });
    
    // Student Dashboard
    // Lecturer Dashboard
    Route::prefix('lecturer')->name('lecturer.')->middleware('role:lecturer')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Lecturer\LecturerController::class, 'dashboard'])->name('dashboard');
        
        // Courses
        Route::get('/courses', [\App\Http\Controllers\Lecturer\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [\App\Http\Controllers\Lecturer\CourseController::class, 'show'])->name('courses.show');
        
        // Assessments
        Route::get('/assessments', [\App\Http\Controllers\Lecturer\AssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/create', [\App\Http\Controllers\Lecturer\AssessmentController::class, 'create'])->name('assessments.create');
        Route::post('/assessments', [\App\Http\Controllers\Lecturer\AssessmentController::class, 'store'])->name('assessments.store');
        Route::get('/assessments/{assessment}/submissions', [\App\Http\Controllers\Lecturer\SubmissionController::class, 'index'])->name('assessments.submissions');
        
        // Results
        Route::get('/results', [\App\Http\Controllers\Lecturer\ResultController::class, 'index'])->name('results.index');
        Route::get('/upload-marks', [\App\Http\Controllers\Lecturer\LecturerController::class, 'showUploadMarksForm'])->name('upload-marks');
        Route::post('/upload-marks', [\App\Http\Controllers\Lecturer\LecturerController::class, 'uploadMarks'])->name('upload-marks.store');
        
        // Attendance
        Route::get('/attendance', [\App\Http\Controllers\Lecturer\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [\App\Http\Controllers\Lecturer\AttendanceController::class, 'store'])->name('attendance.store');
        
        // Students
        Route::get('/students', [\App\Http\Controllers\Lecturer\StudentController::class, 'index'])->name('students.index');
        
        // Profile
        Route::get('/profile', [\App\Http\Controllers\Lecturer\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Lecturer\ProfileController::class, 'update'])->name('profile.update');
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
