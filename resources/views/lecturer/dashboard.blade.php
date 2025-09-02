@extends('layouts.lecturer')

@section('title', 'Lecturer Dashboard')

@push('styles')
<link href="{{ asset('css/lecturer.css') }}" rel="stylesheet">
<style>
    /* Custom Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
        border-left: 2px solid #e3e6f0;
        padding-left: 2rem;
    }
    
    .timeline-item:last-child {
        border-left: 2px solid transparent;
    }
    
    .timeline-marker {
        position: absolute;
        left: -0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background-color: #4e73df;
        margin-top: 0.25rem;
    }
    
    .timeline-content {
        margin-left: 1rem;
    }
    
    .timeline-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .timeline-description {
        color: #5a5c69;
        margin-bottom: 0.25rem;
    }
    
    .timeline-time {
        font-size: 0.8rem;
        color: #858796;
    }
    
    /* Custom Card Styles */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    /* Custom Colors */
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .text-primary {
        color: #4e73df !important;
    }
    
    .text-success {
        color: #1cc88a !important;
    }
    
    .text-warning {
        color: #f6c23e !important;
    }
    
    .text-info {
        color: #36b9cc !important;
    }
    
    /* Custom Buttons */
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .btn-outline-primary {
        color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: #fff;
    }
    
    /* Custom Table Styles */
    .table {
        color: #5a5c69;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05rem;
        color: #5a5c69;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 1.5rem;
        }
        
        .timeline {
            padding-left: 1rem;
        }
        
        .timeline-item {
            padding-left: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-dark">Dashboard</h1>
    <div class="d-flex">
        <a href="{{ route('lecturer.upload-marks') }}" class="btn btn-primary shadow-sm mr-2">
            <i class="fas fa-upload fa-sm text-white-50"></i> Upload Marks
        </a>
        <a href="#" class="btn btn-success shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <!-- Assigned Courses Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Assigned Courses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assignedCoursesCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Students Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudentsCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Submissions Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Submissions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingSubmissionsCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Upcoming Deadlines</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcomingDeadlinesCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Assigned Courses -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">My Assigned Courses</h6>
                <a href="{{ route('lecturer.courses.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye fa-sm"></i> View All
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="coursesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Program</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignedCourses as $course)
                                <tr>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->program->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $course->enrollments_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('lecturer.courses.show', $course) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('lecturer.attendance.index') }}?course={{ $course->id }}" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Attendance">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">No courses assigned yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Results -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Results</h6>
                <a href="{{ route('lecturer.results.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list fa-sm"></i> View All
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="resultsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Assessment</th>
                                <th>Score</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentResults as $result)
                                <tr>
                                    <td>{{ $result->student->name ?? 'N/A' }}</td>
                                    <td>{{ $result->course->code ?? 'N/A' }}</td>
                                    <td>{{ $result->assessment_type }}</td>
                                    <td>
                                        <span class="badge {{ $result->score >= 50 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $result->score }}%
                                        </span>
                                    </td>
                                    <td>{{ $result->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">No recent results found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- Pending Submissions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Pending Submissions</h6>
                <a href="{{ route('lecturer.assessments.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list fa-sm"></i> View All
                </a>
            </div>
            <div class="card-body">
                @forelse($pendingSubmissions as $submission)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="font-weight-bold mb-1">{{ $submission->title }}</h6>
                                <p class="small text-muted mb-1">{{ $submission->course->code }} - {{ $submission->course->name }}</p>
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $submission->submission_progress >= 50 ? 'success' : 'warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ $submission->submission_progress }}%" 
                                         aria-valuenow="{{ $submission->submission_progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>{{ $submission->submitted_count }}/{{ $submission->total_students }} submitted</span>
                                    <span>{{ $submission->submission_progress }}%</span>
                                </div>
                            </div>
                            <a href="{{ route('lecturer.assessments.submissions', $submission) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="text-muted">No pending submissions.</div>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Upcoming Deadlines -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Upcoming Deadlines</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($upcomingDeadlines as $deadline)
                        <div class="list-group-item px-0 py-2 border-0">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 text-center">
                                    <div class="bg-primary text-white rounded p-2">
                                        <div class="font-weight-bold">{{ $deadline->due_date->format('M') }}</div>
                                        <div class="h5 mb-0">{{ $deadline->due_date->format('d') }}</div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $deadline->title }}</h6>
                                    <p class="small text-muted mb-0">
                                        {{ $deadline->course->code }} - {{ $deadline->course->name }}
                                    </p>
                                    <small class="text-{{ $deadline->due_date->isToday() ? 'danger' : 'muted' }}">
                                        <i class="far fa-clock"></i> 
                                        {{ $deadline->due_date->isToday() ? 'Today' : $deadline->due_date->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <div class="text-muted">No upcoming deadlines.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('lecturer.upload-marks') }}" class="btn btn-block btn-primary mb-2">
                    <i class="fas fa-upload mr-2"></i> Upload Marks
                </a>
                <a href="{{ route('lecturer.assessments.create') }}" class="btn btn-block btn-success mb-2">
                    <i class="fas fa-plus-circle mr-2"></i> Create Assessment
                </a>
                <a href="{{ route('lecturer.attendance.index') }}" class="btn btn-block btn-info text-white mb-2">
                    <i class="fas fa-clipboard-check mr-2"></i> Take Attendance
                </a>
                <a href="{{ route('lecturer.profile.edit') }}" class="btn btn-block btn-secondary">
                    <i class="fas fa-user-edit mr-2"></i> Update Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#coursesTable, #resultsTable').DataTable({
            "pageLength": 5,
            "order": [[0, 'asc']]
        });
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
