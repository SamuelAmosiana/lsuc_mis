<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Role-based dashboards (placeholders)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/admin/dashboard', 'dashboard')->middleware('role:super_admin,admin')->name('admin.dashboard');
    Route::view('/coordinator/dashboard', 'dashboard')->middleware('role:programme_coordinator')->name('coordinator.dashboard');
    Route::view('/accounts/dashboard', 'dashboard')->middleware('role:accounts')->name('accounts.dashboard');
    Route::view('/lecturer/dashboard', 'dashboard')->middleware('role:lecturer')->name('lecturer.dashboard');
    Route::view('/student/dashboard', 'dashboard')->middleware('role:student')->name('student.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin CRUD routes
Route::middleware(['auth','verified','role:super_admin,admin'])->group(function () {
    Volt::route('admin', 'admin.dashboard')->name('admin.home');
    Volt::route('admin/departments', 'admin.departments')->name('admin.departments');
    Volt::route('admin/schools', 'admin.schools')->name('admin.schools');
    Volt::route('admin/programmes', 'admin.programmes')->name('admin.programmes');
    Volt::route('admin/courses', 'admin.courses')->name('admin.courses');
    Volt::route('admin/users', 'admin.users')->name('admin.users');
    Volt::route('admin/roles', 'admin.roles')->name('admin.roles');
    Volt::route('admin/settings', 'admin.settings')->name('admin.settings');
});

require __DIR__.'/auth.php';
