<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the submissions for an assessment.
     */
    public function index(Assessment $assessment)
    {
        $submissions = $assessment->submissions()
            ->with(['student', 'grading'])
            ->latest()
            ->paginate(10);

        return view('lecturer.assessments.submissions', compact('assessment', 'submissions'));
    }
}
