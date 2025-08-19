<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;

class HRController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_staff' => User::role('staff')->count(),
            'present_today' => Attendance::whereDate('date', today())->distinct('user_id')->count(),
            'pending_leave' => LeaveRequest::where('status', 'pending')->count(),
        ];

        return view('hr.dashboard', compact('stats'));
    }
}
