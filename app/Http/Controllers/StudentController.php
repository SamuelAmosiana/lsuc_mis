<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\CourseLSC;
use App\Models\Fee;
use App\Models\AccommodationAssignment;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:student']);
    }

    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            // If no student record exists, create a basic one with default values
            $student = (object)[
                'id' => null,
                'student_id' => $user->id,
                'programme' => null,
                'courses' => collect(),
                'accommodationAssignment' => null,
                'balance' => 0,
                'results' => collect(),
            ];
            
            // You might want to redirect to a profile completion page here
            // return redirect()->route('student.profile')->with('warning', 'Please complete your student profile.');
        } else {
            // Load relationships if student exists
            $student->load([
                'programme',
                'courses',
                'accommodationAssignment',
                'results' => function($query) {
                    $query->latest()->take(5);
                }
            ]);
        }
 
        return view('student.dashboard', compact('student'));
    }

    public function profile()
    {
        $student = Auth::user()->student->load('programme');
        return view('student.profile', compact('student'));
    }

    public function finances()
    {
        $student = Auth::user()->student->load('transactions');
        return view('student.finances', compact('student'));
    }

    public function courses()
    {
        $student = Auth::user()->student;
        $availableCourses = CourseLSC::where('programme_id', $student->programme_id)
            ->whereNotIn('id', $student->courses->pluck('id'))
            ->get();
            
        return view('student.courses', [
            'enrolledCourses' => $student->courses,
            'availableCourses' => $availableCourses
        ]);
    }

    public function registerCourses(Request $request)
    {
        $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id'
        ]);

        $student = Auth::user()->student;
        $student->courses()->syncWithoutDetaching($request->course_ids);

        return back()->with('success', 'Courses registered successfully!');
    }

    public function accommodation()
    {
        $student = Auth::user()->student->load('accommodationAssignment.room');
        $accommodationStatus = $student->accommodationAssignment 
            ? 'assigned' 
            : ($student->accommodationApplication ? 'pending' : 'not_applied');
            
        return view('student.accommodation', [
            'accommodation' => $student->accommodationAssignment,
            'status' => $accommodationStatus
        ]);
    }

    public function applyAccommodation(Request $request)
    {
        $student = Auth::user()->student;
        
        // Check if already applied or assigned
        if ($student->accommodationApplication || $student->accommodationAssignment) {
            return back()->with('error', 'You have already applied for accommodation.');
        }

        // Create accommodation application
        $student->accommodationApplication()->create([
            'academic_year' => now()->year,
            'semester' => $this->getCurrentSemester(),
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Accommodation application submitted successfully!');
    }

    public function docket()
    {
        return view('student.docket');
    }

    /**
     * Display the student's timetable.
     *
     * @return \Illuminate\View\View
     */
    public function timetable()
    {
        // Mock data - in a real application, this would come from the database
        $currentSemester = 'First Semester';
        $academicYear = '2024/2025';
        $currentSemesterId = 1;
        
        // Sample semesters data
        $semesters = collect([
            (object)[
                'id' => 1,
                'name' => 'First Semester',
                'academic_year' => '2024/2025',
                'start_date' => '2024-09-02',
                'end_date' => '2024-12-20',
            ],
            (object)[
                'id' => 2,
                'name' => 'Second Semester',
                'academic_year' => '2024/2025',
                'start_date' => '2025-01-13',
                'end_date' => '2025-05-02',
            ],
        ]);

        // Days of the week
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        // Time slots
        $timeSlots = [
            '08:00:00' => '8:00 AM - 9:00 AM',
            '09:00:00' => '9:00 AM - 10:00 AM',
            '10:00:00' => '10:00 AM - 11:00 AM',
            '11:00:00' => '11:00 AM - 12:00 PM',
            '12:00:00' => '12:00 PM - 1:00 PM',
            '13:00:00' => '1:00 PM - 2:00 PM',
            '14:00:00' => '2:00 PM - 3:00 PM',
            '15:00:00' => '3:00 PM - 4:00 PM',
            '16:00:00' => '4:00 PM - 5:00 PM',
        ];

        // Sample timetable data - in a real app, this would come from the database
        $timetable = [
            [
                'course_code' => 'CS101',
                'title' => 'Introduction to Computer Science',
                'type' => 'lecture',
                'day' => 'Monday',
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'room' => 'CS101',
                'lecturer' => 'Dr. Smith',
            ],
            [
                'course_code' => 'MATH201',
                'title' => 'Linear Algebra',
                'type' => 'lecture',
                'day' => 'Monday',
                'start_time' => '11:00:00',
                'end_time' => '12:30:00',
                'room' => 'MATH202',
                'lecturer' => 'Prof. Johnson',
            ],
            [
                'course_code' => 'CS101',
                'title' => 'Introduction to Computer Science',
                'type' => 'tutorial',
                'day' => 'Wednesday',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'room' => 'CS102',
                'lecturer' => 'Dr. Smith',
            ],
            [
                'course_code' => 'PHYS101',
                'title' => 'Physics for Engineers',
                'type' => 'lecture',
                'day' => 'Tuesday',
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'room' => 'PHY101',
                'lecturer' => 'Dr. Brown',
            ],
            [
                'course_code' => 'MATH201',
                'title' => 'Linear Algebra',
                'type' => 'tutorial',
                'day' => 'Thursday',
                'start_time' => '14:00:00',
                'end_time' => '15:30:00',
                'room' => 'MATH203',
                'lecturer' => 'Prof. Johnson',
            ],
            [
                'course_code' => 'ENG101',
                'title' => 'Technical Writing',
                'type' => 'lecture',
                'day' => 'Friday',
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'room' => 'ENG101',
                'lecturer' => 'Dr. Williams',
            ],
        ];

        // Sample exam schedule
        $exams = [
            [
                'course_code' => 'CS101',
                'title' => 'Introduction to Computer Science',
                'date' => now()->addWeeks(6),
                'start_time' => '09:00',
                'end_time' => '12:00',
                'venue' => 'Main Hall',
                'status' => 'upcoming',
            ],
            [
                'course_code' => 'MATH201',
                'title' => 'Linear Algebra',
                'date' => now()->addWeeks(6)->addDay(),
                'start_time' => '09:00',
                'end_time' => '12:00',
                'venue' => 'Maths Block A',
                'status' => 'upcoming',
            ],
            [
                'course_code' => 'PHYS101',
                'title' => 'Physics for Engineers',
                'date' => now()->addWeeks(7),
                'start_time' => '09:00',
                'end_time' => '12:00',
                'venue' => 'Physics Lab 1',
                'status' => 'upcoming',
            ],
        ];

        return view('student.timetable', [
            'currentSemester' => $currentSemester,
            'academicYear' => $academicYear,
            'currentSemesterId' => $currentSemesterId,
            'semesters' => $semesters,
            'days' => $days,
            'timeSlots' => $timeSlots,
            'timetable' => $timetable,
            'exams' => $exams,
        ]);
    }

    public function results()
    {
        $student = Auth::user()->student->load(['results.course', 'programme']);
        return view('student.results', compact('student'));
    }

    private function getCurrentSemester()
    {
        $month = now()->month;
        return ($month >= 1 && $month <= 6) ? 'Spring' : 'Fall';
    }
}
