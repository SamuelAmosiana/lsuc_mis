<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'user' => auth()->user()
        ]);
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
