<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the results.
     */
    public function index()
    {
        $user = auth()->user();
        $courses = $user->courses()
            ->with(['enrollments.student', 'assessments.submissions'])
            ->get();

        return view('lecturer.results.index', compact('courses'));
    }
}
