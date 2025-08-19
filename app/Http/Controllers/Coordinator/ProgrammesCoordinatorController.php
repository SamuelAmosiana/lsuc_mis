<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\CourseRegistration;
use App\Models\AcademicCalendar;
use App\Models\Timetable;
use App\Models\Department;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ProgrammesCoordinatorController extends Controller
{
    /**
     * Display the coordinator dashboard.
     */
    public function dashboard()
    {
        // Get current user's staff record
        $user = auth()->user();
        $staff = Staff::where('email', $user->email)->first();
        
        // Get department programs count
        $programsCount = $staff->department ? $staff->department->programs()->count() : 0;
        
        // Get pending registrations count
        $pendingRegistrations = CourseRegistration::where('status', 'pending')
            ->when($staff->department, function($query) use ($staff) {
                return $query->whereHas('course', function($q) use ($staff) {
                    $q->where('department_id', $staff->department_id);
                });
            })
            ->count();
            
        // Get upcoming events (next 7 days)
        $upcomingEvents = AcademicCalendar::where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(7))
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        // Get total number of lecturers in the same department
        $lecturers = Staff::whereHas('roles', function($q) {
                $q->where('role_name', 'lecturer');
            })
            ->when($staff->department, function($query) use ($staff) {
                return $query->where('department_id', $staff->department_id);
            })
            ->count();
            
        // Get recent registrations for the department
        $recentRegistrations = CourseRegistration::with(['student', 'course'])
            ->when($staff->department, function($query) use ($staff) {
                return $query->whereHas('course', function($q) use ($staff) {
                    $q->where('department_id', $staff->department_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('coordinator.dashboard', compact(
            'programsCount', 
            'pendingRegistrations',
            'upcomingEvents',
            'lecturers',
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
        $user = auth()->user();
        $staff = Staff::where('email', $user->email)->first();
        
        return view('coordinator.student-registrations', [
            'registrations' => CourseRegistration::with(['student', 'course'])
                ->where('status', 'pending')
                ->when($staff && $staff->department, function($query) use ($staff) {
                    return $query->whereHas('course', function($q) use ($staff) {
                        $q->where('department_id', $staff->department_id);
                    });
                })
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

    /**
     * Approve a course registration.
     */
    public function approveRegistration(StudentRegistration $registration): JsonResponse
    {
        try {
            // Ensure the registration is pending
            if ($registration->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This registration has already been processed.'
                ], 400);
            }

            // Update the registration status
            $registration->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a course registration.
     */
    public function rejectRegistration(Request $request, StudentRegistration $registration): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            // Ensure the registration is pending
            if ($registration->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This registration has already been processed.'
                ], 400);
            }

            // Update the registration status
            $registration->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => $request->input('reason')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration rejected successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject registration: ' . $e->getMessage()
            ], 500);
        }
    }
}
