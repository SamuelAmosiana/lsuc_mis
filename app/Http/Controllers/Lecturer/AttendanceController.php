<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $courseId = $request->query('course');
        
        $courses = $user->courses()->pluck('name', 'id');
        $attendances = [];
        $selectedCourse = null;
        
        if ($courseId) {
            $selectedCourse = Course::findOrFail($courseId);
            $attendances = Attendance::where('course_id', $courseId)
                ->with(['student', 'recordedBy'])
                ->latest()
                ->paginate(20);
        }
        
        return view('lecturer.attendance.index', compact('courses', 'attendances', 'selectedCourse'));
    }
    
    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'student_id' => 'required|exists:students,student_id',
            'status' => 'required|in:present,absent,late,excused',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        $validated['recorded_by'] = auth()->id();
        
        Attendance::create($validated);
        
        return redirect()->back()->with('success', 'Attendance recorded successfully.');
    }
}
