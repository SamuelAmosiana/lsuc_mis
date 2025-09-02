<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the assessments.
     */
    public function index()
    {
        $user = auth()->user();
        $assessments = Assessment::whereIn('course_id', $user->courses()->pluck('id'))
            ->with(['course', 'submissions'])
            ->latest()
            ->paginate(10);

        return view('lecturer.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create()
    {
        $user = auth()->user();
        $courses = $user->courses()->pluck('name', 'id');
        
        return view('lecturer.assessments.create', compact('courses'));
    }

    /**
     * Store a newly created assessment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'total_marks' => 'required|numeric|min:1',
        ]);

        Assessment::create($validated);

        return redirect()->route('lecturer.assessments.index')
            ->with('success', 'Assessment created successfully.');
    }
}
