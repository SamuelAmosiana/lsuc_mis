<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     * This is handled by Livewire Volt component at resources/views/livewire/admin/dashboard.blade.php
     */
    public function dashboard()
    {
        return redirect()->route('admin.home');
    }

    /**
     * Display user management page.
     */
    public function userManagement(): View
    {
        // TODO: Add user management logic
        return view('admin.user-management');
    }

    /**
     * Display reports.
     */
    public function reports(): View
    {
        // TODO: Add reports logic
        return view('admin.reports');
    }
}
