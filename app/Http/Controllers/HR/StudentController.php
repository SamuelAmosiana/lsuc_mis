<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'program']);
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by program
        if ($request->has('program_id') && $request->program_id) {
            $query->where('program_id', $request->program_id);
        }
        
        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active', 'inactive', 'suspended', 'graduated'])) {
            $query->where('status', $request->status);
        }
        
        $students = $query->latest()->paginate(15);
        $programs = Program::all();
        
        return view('hr.students.index', compact('students', 'programs'));
    }
    
    public function show(Student $student)
    {
        $student->load(['user', 'program', 'enrollments.course', 'documents']);
        return view('hr.students.show', compact('student'));
    }
    
    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended,graduated',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        DB::transaction(function () use ($student, $request) {
            $student->update([
                'status' => $request->status,
                'status_updated_at' => now(),
                'status_updated_by' => auth()->id(),
            ]);
            
            if ($request->notes) {
                $student->statusNotes()->create([
                    'note' => $request->notes,
                    'created_by' => auth()->id(),
                ]);
            }
            
            // If status is set to graduated, mark all enrollments as completed
            if ($request->status === 'graduated') {
                $student->enrollments()->update(['status' => 'completed']);
            }
        });
        
        return back()->with('success', 'Student status updated successfully');
    }
    
    public function getStudentStats()
    {
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'suspended' => Student::where('status', 'suspended')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'programs' => Program::withCount('students')->get(),
        ];
        
        return response()->json($stats);
    }
}
