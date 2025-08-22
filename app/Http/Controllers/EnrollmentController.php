<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnrollmentStatusUpdated;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments with filters.
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'program']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('enrollment_type', $request->type);
        }

        if ($request->has('needs_interview')) {
            $query->whereNull('interview_date')
                 ->orWhereNull('interview_time');
        }

        if ($request->has('needs_accommodation')) {
            $query->where('needs_accommodation', true)
                 ->where('accommodation_status', '!=', 'assigned');
        }

        $enrollments = $query->latest()->paginate(15);
        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $students = User::where('role', 'student')->get();
        $programs = Program::all();
        return view('enrollments.create', compact('students', 'programs'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
            'enrollment_type' => 'required|in:online,physical',
            'notes' => 'nullable|string',
            'needs_accommodation' => 'boolean',
        ]);

        $enrollment = Enrollment::create([
            'student_id' => $validated['student_id'],
            'program_id' => $validated['program_id'],
            'enrollment_type' => $validated['enrollment_type'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'needs_accommodation' => $validated['needs_accommodation'] ?? false,
            'accommodation_status' => $validated['needs_accommodation'] ? 'requested' : 'not_requested',
        ]);

        return redirect()->route('enrollments.show', $enrollment)
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student', 'program']);
        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the enrollment.
     */
    public function edit(Enrollment $enrollment)
    {
        $programs = Program::all();
        return view('enrollments.edit', compact('enrollment', 'programs'));
    }

    /**
     * Update the enrollment in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,waitlisted',
            'interview_date' => 'nullable|date',
            'interview_time' => 'nullable|date_format:H:i',
            'interview_notes' => 'nullable|string',
            'accommodation_status' => 'required_if:needs_accommodation,true|in:not_requested,requested,assigned,rejected',
            'notes' => 'nullable|string',
        ]);

        $originalStatus = $enrollment->status;
        
        $enrollment->update($validated);

        // Send notification if status changed
        if ($originalStatus !== $enrollment->status) {
            Mail::to($enrollment->student->email)
                ->send(new EnrollmentStatusUpdated($enrollment));
        }

        return redirect()->route('enrollments.show', $enrollment)
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Schedule an interview for the enrollment.
     */
    public function scheduleInterview(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'interview_date' => 'required|date',
            'interview_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $enrollment->update([
            'interview_date' => $validated['interview_date'],
            'interview_time' => $validated['interview_time'],
            'interview_notes' => $validated['notes'] ?? null,
        ]);

        // Send interview scheduled notification
        // Mail::to($enrollment->student->email)
        //     ->send(new InterviewScheduled($enrollment));

        return back()->with('success', 'Interview scheduled successfully.');
    }

    /**
     * Update accommodation status.
     */
    public function updateAccommodation(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'accommodation_status' => 'required|in:requested,assigned,rejected',
            'notes' => 'nullable|string',
        ]);

        $enrollment->update([
            'accommodation_status' => $validated['accommodation_status'],
            'notes' => $enrollment->notes . "\n\n" . ($validated['notes'] ?? ''),
        ]);

        return back()->with('success', 'Accommodation status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
