@extends('layouts.lecturer')

@section('title', $course->name)

@push('styles')
<style>
    .student-row {
        transition: background-color 0.3s;
    }
    .student-row:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Course Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $course->name }}</h1>
        <div>
            <a href="{{ route('lecturer.courses.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Back to Courses
            </a>
        </div>
    </div>

    <!-- Course Info -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Course Code</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $course->code }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Program</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $course->program->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Enrolled Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $course->enrollments_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <ul class="nav nav-tabs card-header-tabs" id="courseTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students" role="tab" aria-controls="students" aria-selected="true">
                        <i class="fas fa-users mr-1"></i> Students
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="assessments-tab" data-toggle="tab" href="#assessments" role="tab" aria-controls="assessments" aria-selected="false">
                        <i class="fas fa-tasks mr-1"></i> Assessments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab" aria-controls="attendance" aria-selected="false">
                        <i class="fas fa-calendar-check mr-1"></i> Attendance
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="{{ route('lecturer.attendance.index') }}?course={{ $course->id }}" class="btn btn-info btn-sm text-white mr-2">
                    <i class="fas fa-clipboard-check mr-1"></i> Take Attendance
                </a>
                <a href="{{ route('lecturer.upload-marks') }}?course={{ $course->id }}" class="btn btn-success btn-sm">
                    <i class="fas fa-upload mr-1"></i> Upload Marks
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="courseTabsContent">
                <!-- Students Tab -->
                <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                    <div class="table-responsive">
                        <table class="table table-hover" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Program</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($course->enrollments as $enrollment)
                                    <tr class="student-row">
                                        <td>{{ $enrollment->student->student_id ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->student->name ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->student->email ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->program->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View Profile">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">No students enrolled in this course.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Assessments Tab -->
                <div class="tab-pane fade" id="assessments" role="tabpanel" aria-labelledby="assessments-tab">
                    <div class="mb-3">
                        <a href="#" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Add Assessment
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="assessmentsTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Max Marks</th>
                                    <th>Submissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($course->assessments ?? [] as $assessment)
                                    <tr>
                                        <td>{{ $assessment->title }}</td>
                                        <td>{{ $assessment->type }}</td>
                                        <td>{{ $assessment->due_date->format('M d, Y') }}</td>
                                        <td>{{ $assessment->max_marks }}</td>
                                        <td>{{ $assessment->submissions_count ?? 0 }}/{{ $course->enrollments_count }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View Submissions">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Grade">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">No assessments found for this course.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Attendance Tab -->
                <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Attendance records will be displayed here. Use the "Take Attendance" button to record attendance.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Late</th>
                                    <th>Excused</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">No attendance records found.</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#studentsTable, #assessmentsTable, #attendanceTable').DataTable({
            "pageLength": 10,
            "order": [[0, 'asc']]
        });
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Handle tab persistence
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('lastTab', $(e.target).attr('href'));
        });
        
        // Get the last tab from localStorage
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
    });
</script>
@endpush
