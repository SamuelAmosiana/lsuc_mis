<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\CourseRegistration;
use App\Models\AcademicCalendar;
use App\Models\Timetable;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProgrammesCoordinatorController extends Controller
{
    /**
     * Display the coordinator dashboard.
     */
    public function dashboard()
    {
        // Get current user's staff record
        $user = auth()->user();
        $staff = $user->staff;
        
        // Get department programs count
        $programsCount = $staff->department->programs()->count() ?? 0;
        
        // Get pending registrations count
        $pendingRegistrations = CourseRegistration::where('status', 'pending')
            ->whereHas('course', function($q) use ($staff) {
                $q->where('department_id', $staff->department_id);
            })
            ->count();
            
        // Get upcoming events
        $upcomingEvents = AcademicCalendar::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        // Get recent registrations
        $recentRegistrations = CourseRegistration::with(['student', 'course'])
            ->whereHas('course', function($q) use ($staff) {
                $q->where('department_id', $staff->department_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('coordinator.dashboard', compact(
            'programsCount', 
            'pendingRegistrations',
            'upcomingEvents',
            'recentRegistrations'
        ));
    }

    /**
     * Display academic calendar management.
     */
    public function academicCalendar()
    {
        $events = AcademicCalendar::orderBy('start_date')->paginate(10);
        $eventTypes = AcademicCalendar::select('event_type')->distinct()->pluck('event_type');
        
        return view('coordinator.academic-calendar', compact('events', 'eventTypes'));
    }

    /**
     * Display timetable management.
     */
    public function timetables(): View
    {
        return view('coordinator.timetables', [
            'timetables' => Timetable::with(['course', 'lecturer', 'room'])
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->paginate(15)
        ]);
    }

    /**
     * Display student registrations for approval.
     */
    public function studentRegistrations(): View
    {
        return view('coordinator.student-registrations', [
            'registrations' => StudentRegistration::with(['student', 'course'])
                ->where('status', 'pending')
                ->latest()
                ->paginate(15)
        ]);
    }

    /**
     * Display lecturer attendance tracking.
     */
    public function lecturerAttendance(): View
    {
        $today = Carbon::today();
        
        return view('coordinator.lecturer-attendance', [
            'lecturers' => Staff::with(['user', 'department'])
                ->where('staff_type', 'lecturer')
                ->paginate(15),
            'today' => $today->format('Y-m-d')
        ]);
    }

    /**
     * Display results management.
     */
    public function results(): View
    {
        return view('coordinator.results', [
            'courses' => \App\Models\CourseLSC::with(['programme', 'department'])
                ->orderBy('course_code')
                ->paginate(15)
        ]);
    }
}
