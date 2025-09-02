<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $courseId = $request->query('course');
        
        $courses = $user->courses()->pluck('name', 'id');
        $students = [];
        $selectedCourse = null;
        
        if ($courseId) {
            $selectedCourse = Course::findOrFail($courseId);
            $students = $selectedCourse->enrollments()
                ->with(['student', 'program'])
                ->paginate(20);
        } else {
            // Show all students enrolled in lecturer's courses
            $enrollments = Enrollment::whereIn('course_id', $user->courses()->pluck('id'))
                ->with(['student', 'course', 'program'])
                ->groupBy('student_id')
                ->paginate(20);
                
            $students = $enrollments->map(function($enrollment) {
                return $enrollment->student;
            });
        }
        
        return view('lecturer.students.index', compact('courses', 'students', 'selectedCourse'));
    }
}
