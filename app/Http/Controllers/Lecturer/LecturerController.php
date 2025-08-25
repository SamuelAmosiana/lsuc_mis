<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Assessment;
use App\Models\Result;
use App\Models\Program;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    /**
     * Display the lecturer dashboard.
     */
    public function dashboard()
    {
        \Log::info('=== START LECTURER DASHBOARD ===');
        
        $lecturer = Auth::user();
        
        // Debug: Log lecturer info
        \Log::info('Lecturer Info', [
            'id' => $lecturer->id,
            'name' => $lecturer->name,
            'email' => $lecturer->email,
            'role' => $lecturer->role,
            'is_authenticated' => Auth::check() ? 'Yes' : 'No'
        ]);
        
        try {
            // Get assigned courses with counts and program info
            $assignedCourses = $lecturer->courses()
                ->withCount(['enrollments', 'assessments'])
                ->with(['program'])
                ->latest()
                ->get();
                
            // Debug: Log assigned courses
            \Log::info('Assigned Courses', [
                'count' => $assignedCourses->count(),
                'courses' => $assignedCourses->map(fn($course) => [
                    'id' => $course->id,
                    'code' => $course->code,
                    'title' => $course->title,
                    'program' => $course->program ? $course->program->name : null,
                    'enrollments_count' => $course->enrollments_count,
                    'assessments_count' => $course->assessments_count
                ])
            ]);
                
            // Calculate total students across all courses
            $totalStudents = $assignedCourses->sum(function($course) {
                return $course->enrollments_count;
            });
            
            // Get pending submissions for the lecturer's courses
            $pendingSubmissions = $this->getPendingSubmissions($lecturer->id);
            \Log::info('Pending Submissions', [
                'count' => count($pendingSubmissions),
                'submissions' => $pendingSubmissions
            ]);
            
            // Get recent results for the lecturer's courses
            $recentResults = $this->getRecentResults($lecturer->id);
            \Log::info('Recent Results', [
                'count' => count($recentResults),
                'results' => $recentResults->map(fn($result) => [
                    'id' => $result->id,
                    'student' => $result->student->name ?? 'N/A',
                    'assessment' => $result->assessment->title ?? 'N/A',
                    'score' => $result->score
                ])
            ]);
            
            // Get recent activity
            $recentActivity = $this->getRecentActivity($lecturer->id);
            \Log::info('Recent Activity', [
                'count' => count($recentActivity),
                'activities' => $recentActivity
            ]);
            
            // Get attendance statistics
            $attendanceStats = $this->getAttendanceStats($lecturer->id);
            \Log::info('Attendance Stats', $attendanceStats);
            
            $viewData = [
                'assignedCourses' => $assignedCourses,
                'totalStudents' => $totalStudents,
                'pendingSubmissions' => $pendingSubmissions,
                'recentResults' => $recentResults,
                'recentActivity' => $recentActivity,
                'attendanceStats' => $attendanceStats,
            ];
            
            \Log::info('Rendering lecturer.dashboard view with data', [
                'assigned_courses_count' => $assignedCourses->count(),
                'total_students' => $totalStudents,
                'pending_submissions_count' => count($pendingSubmissions),
                'recent_results_count' => $recentResults->count(),
                'recent_activity_count' => count($recentActivity)
            ]);
            
            \Log::info('=== END LECTURER DASHBOARD ===');
            
            return view('lecturer.dashboard', $viewData);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in lecturer dashboard: ' . $e->getMessage(), [
                'exception' => $e,
                'lecturer_id' => $lecturer->id
            ]);
            
            // Return a view with an error message
            return view('lecturer.dashboard', [
                'assignedCourses' => collect(),
                'totalStudents' => 0,
                'pendingSubmissions' => [],
                'recentResults' => [],
                'recentActivity' => [],
                'attendanceStats' => [],
                'error' => 'An error occurred while loading the dashboard. Please try again later.'
            ]);
        }
    }

    /**
     * Get pending submissions for a lecturer's courses.
     */
    private function getPendingSubmissions($lecturerId)
    {
        return Assessment::whereHas('course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->with(['course', 'submissions'])
        ->withCount(['submissions', 'enrollments'])
        ->where('deadline', '>=', now()->subDays(7)) // Only show recent and upcoming
        ->orderBy('deadline', 'asc')
        ->get()
        ->filter(function($assessment) {
            return $assessment->submissions_count < $assessment->enrollments_count;
        });
    }

    /**
     * Get recent results for a lecturer's courses.
     */
    private function getRecentResults($lecturerId)
    {
        return Result::whereHas('assessment.course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->with([
            'assessment' => function($q) {
                $q->with('course');
            },
            'student'
        ])
        ->latest()
        ->take(5)
        ->get();
    }

    /**
     * Get recent activity for the lecturer
     */
    private function getRecentActivity($lecturerId)
    {
        // Get recent assessments and results
        $assessments = Assessment::whereHas('course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->select('id', 'title', 'course_id', 'created_at')
        ->with('course')
        ->latest()
        ->take(3)
        ->get()
        ->map(function($item) {
            return (object)[
                'title' => 'New Assessment Created',
                'description' => 'Created ' . $item->title . ' for ' . ($item->course->code ?? 'N/A'),
                'created_at' => $item->created_at,
                'type' => 'assessment'
            ];
        });

        $results = Result::whereHas('assessment.course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->select('id', 'assessment_id', 'student_id', 'created_at')
        ->with(['assessment.course', 'student'])
        ->latest()
        ->take(3)
        ->get()
        ->map(function($item) {
            return (object)[
                'title' => 'Result Recorded',
                'description' => 'Added result for ' . ($item->student->name ?? 'Student') . ' in ' . ($item->assessment->course->code ?? 'N/A'),
                'created_at' => $item->created_at,
                'type' => 'result'
            ];
        });

        // Combine and sort by date
        $activity = $assessments->merge($results)
            ->sortByDesc('created_at')
            ->take(5);

        return $activity;
    }

    /**
     * Get attendance statistics
     */
    private function getAttendanceStats($lecturerId)
    {
        // This is a simplified example - adjust based on your attendance model
        $totalSessions = \App\Models\Attendance::whereHas('course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->where('session_date', '>=', now()->subMonth())
        ->count();

        $presentCount = \App\Models\Attendance::whereHas('course', function($q) use ($lecturerId) {
            $q->where('lecturer_id', $lecturerId);
        })
        ->where('status', 'present')
        ->where('session_date', '>=', now()->subMonth())
        ->count();

        $attendanceRate = $totalSessions > 0 ? round(($presentCount / $totalSessions) * 100) : 0;

        return [
            'total_sessions' => $totalSessions,
            'present_count' => $presentCount,
            'attendance_rate' => $attendanceRate,
        ];
    }

    /**
     * Show the form for uploading CA/Exam marks.
     */
    public function showUploadMarksForm()
    {
        $lecturer = Auth::user();
        $courses = $lecturer->courses()
            ->with('program')
            ->withCount('enrollments')
            ->get();
        
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

}
