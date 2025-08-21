<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Assessment;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    /**
     * Display the lecturer dashboard.
     */
    public function dashboard()
    {
        $lecturer = Auth::user();
        $assignedCourses = $lecturer->courses()->with(['program', 'enrollments.student'])->get();
        
        return view('lecturer.dashboard', [
            'assignedCourses' => $assignedCourses,
            'pendingSubmissions' => $this->getPendingSubmissions($lecturer->id),
            'recentResults' => $this->getRecentResults($lecturer->id),
        ]);
    }

    /**
     * Show the form for uploading CA/Exam marks.
     */
    public function showUploadMarksForm()
    {
        $lecturer = Auth::user();
        $courses = $lecturer->courses()->with('program')->get();
        
        return view('lecturer.upload-marks', [
            'courses' => $courses
        ]);
    }

    /**
     * Handle the upload of CA/Exam marks.
     */
    public function uploadMarks(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'assessment_type' => 'required|in:ca,exam',
            'marks_file' => 'required|file|mimes:csv,xls,xlsx|max:2048'
        ]);

        // Process the file and save marks
        // This is a placeholder - you'll need to implement the actual file processing
        
        return redirect()->route('lecturer.dashboard')
            ->with('success', 'Marks uploaded successfully!');
    }

    /**
     * View course roster for a specific course.
     */
    public function viewCourseRoster(Course $course)
    {
        $this->authorize('view', $course);
        
        $students = $course->enrollments()->with('student')->get()
            ->map(fn($enrollment) => $enrollment->student);
            
        return view('lecturer.course-roster', [
            'course' => $course,
            'students' => $students
        ]);
    }

    /**
     * Generate performance report for a student in a course.
     */
    public function generatePerformanceReport(Course $course, Student $student)
    {
        $this->authorize('view', $course);
        
        $assessments = $course->assessments()->with(['results' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }])->get();
        
        return view('lecturer.performance-report', [
            'course' => $course,
            'student' => $student,
            'assessments' => $assessments
        ]);
    }

    /**
     * Get pending submissions for a lecturer's courses.
     */
    private function getPendingSubmissions($lecturerId)
    {
        return Assessment::whereHas('course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->where('deadline', '<', now())
        ->with(['course', 'submissions'])
        ->get()
        ->filter(fn($assessment) => $assessment->submissions->count() === 0);
    }

    /**
     * Get recent results for a lecturer's courses.
     */
    private function getRecentResults($lecturerId)
    {
        return Result::whereHas('assessment.course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->with(['assessment.course', 'student'])
        ->latest()
        ->take(5)
        ->get();
    }
}
