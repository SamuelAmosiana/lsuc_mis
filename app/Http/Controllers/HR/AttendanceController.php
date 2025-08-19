<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        $attendances = Attendance::with('user')
            ->whereDate('date', $today)
            ->latest()
            ->paginate(15);

        $staff = User::role('staff')->get();
        
        return view('hr.attendance.index', compact('attendances', 'staff', 'today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,late,on_leave',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        Attendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
                'notes' => $request->notes,
                'recorded_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Attendance recorded successfully');
    }
}
