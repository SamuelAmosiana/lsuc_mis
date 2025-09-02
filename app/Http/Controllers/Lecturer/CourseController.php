<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses assigned to the lecturer.
     */
    public function index()
    {
        $user = auth()->user();
        $courses = $user->courses()
            ->with(['program', 'enrollments'])
            ->withCount('enrollments')
            ->get();

        return view('lecturer.courses.index', compact('courses'));
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['program', 'enrollments.student']);
        
        return view('lecturer.courses.show', compact('course'));
    }
}
