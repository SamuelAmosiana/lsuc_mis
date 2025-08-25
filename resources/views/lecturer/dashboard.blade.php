@extends('layouts.lecturer')

@section('title', 'Lecturer Dashboard')

@push('styles')
<link href="{{ asset('css/lecturer.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-dark">Dashboard</h1>
    <div class="d-flex">
        <a href="{{ route('lecturer.upload-marks') }}" class="btn btn-lsuc-orange shadow-sm mr-2">
            <i class="fas fa-upload fa-sm text-white-50"></i> Upload Marks
        </a>
        <a href="#" class="btn btn-lsuc-green shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <!-- Students Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="border-left: 4px solid var(--lsuc-green) !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value text-lsuc-green mb-1">{{ $totalStudents ?? 0 }}</div>
                        <div class="stat-label">Total Students</div>
                    </div>
                    <div class="icon-circle bg-lsuc-green">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="border-left: 4px solid var(--lsuc-orange) !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value text-lsuc-orange mb-1">{{ $assignedCourses->count() }}</div>
                        <div class="stat-label">Assigned Courses</div>
                    </div>
                    <div class="icon-circle bg-lsuc-orange">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Submissions Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="border-left: 4px solid #36b9cc !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value text-info mb-1">{{ count($pendingSubmissions) }}</div>
                        <div class="stat-label">Pending Submissions</div>
                    </div>
                    <div class="icon-circle bg-info">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Rate Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value text-warning mb-1">{{ $attendanceStats['attendance_rate'] ?? 0 }}%</div>
                        <div class="stat-label">Avg. Attendance</div>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Assigned Courses -->
    <div class="col-lg-8 mb-4">
        <!-- Courses Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">My Assigned Courses</h6>
                <a href="{{ route('lecturer.courses') }}" class="btn btn-sm btn-lsuc-green">
                    <i class="fas fa-eye fa-sm"></i> View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th class="text-center">Program</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignedCourses->take(5) as $course)
                                <tr>
                                    <td class="font-weight-bold">{{ $course->code }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-lsuc-green">{{ $course->program->short_name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-weight-bold">{{ $course->enrollments_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('lecturer.courses.show', $course) }}" class="btn btn-sm btn-lsuc-orange" data-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('lecturer.attendance', ['course' => $course->id]) }}" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Take Attendance">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">No courses assigned yet</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Results -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Recent Results</h6>
                <a href="{{ route('lecturer.results') }}" class="btn btn-sm btn-lsuc-green">
                    <i class="fas fa-list fa-sm"></i> View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th class="text-center">Assessment</th>
                                <th class="text-center">Score</th>
                                <th class="text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentResults as $result)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $result->student->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($result->student->name).'&background=random' }}" 
                                                 class="rounded-circle mr-2" width="30" height="30" alt="Student">
                                            <div>
                                                <div class="font-weight-bold">{{ $result->student->name }}</div>
                                                <small class="text-muted">{{ $result->student->student_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $result->assessment->course->code }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-lsuc-orange">{{ $result->assessment->title }}</span>
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        {{ $result->score }}/{{ $result->assessment->total_marks }}
                                        <div class="progress mt-1" style="height: 5px;">
                                            @php
                                                $percentage = ($result->score / $result->assessment->total_marks) * 100;
                                                $progressClass = $percentage >= 70 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                                            @endphp
                                            <div class="progress-bar {{ $progressClass }}" role="progressbar" 
                                                 style="width: {{ $percentage }}%" 
                                                 aria-valuenow="{{ $percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{ $result->created_at->format('M d, Y') }}
                                        <div class="text-muted small">{{ $result->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">No recent results available</div>
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
    <div class="col-lg-4 mb-4">
        <!-- Pending Submissions -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Pending Submissions</h6>
                <a href="{{ route('lecturer.assessments') }}" class="btn btn-sm btn-lsuc-orange">
                    <i class="fas fa-list fa-sm"></i> View All
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($pendingSubmissions->take(5) as $submission)
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0">{{ $submission->title }}</h6>
                            <span class="badge badge-{{ $submission->due_date->isPast() ? 'danger' : 'warning' }}">
                                {{ $submission->due_date->isPast() ? 'Overdue' : 'Due: '.$submission->due_date->format('M d') }}
                            </span>
                        </div>
                        <p class="small text-muted mb-2">{{ $submission->course->code }} - {{ $submission->course->name }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="progress flex-grow-1 mr-3" style="height: 5px;">
                                @php
                                    $submissionRate = $submission->enrollments_count > 0 
                                        ? ($submission->submissions_count / $submission->enrollments_count) * 100 
                                        : 0;
                                @endphp
                                <div class="progress-bar bg-{{ $submissionRate >= 80 ? 'success' : ($submissionRate >= 50 ? 'warning' : 'danger') }}" 
                                     role="progressbar" 
                                     style="width: {{ $submissionRate }}%" 
                                     aria-valuenow="{{ $submissionRate }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ $submission->submissions_count }}/{{ $submission->enrollments_count }}</small>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('lecturer.assessments.submissions', $submission) }}" class="btn btn-sm btn-outline-primary btn-block">
                                <i class="fas fa-arrow-right mr-1"></i> View Submissions
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="text-muted">No pending submissions</div>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Recent Activity</h6>
            </div>
            <div class="card-body">
                <div class="recent-activity">
                    @forelse($recentActivity as $activity)
                        <div class="activity-item">
                            <div class="activity-content">
                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                <p class="small mb-1">{{ $activity->description }}</p>
                                <div class="activity-time small">{{ $activity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <div class="text-muted">No recent activity</div>
                        </div>
                    @endforelse
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-sm btn-outline-lsuc-green">View All Activity</a>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('lecturer.upload-marks') }}" class="btn btn-block btn-lsuc-orange mb-2">
                    <i class="fas fa-upload mr-2"></i> Upload Marks
                </a>
                <a href="{{ route('lecturer.assessments.create') }}" class="btn btn-block btn-outline-lsuc-green mb-2">
                    <i class="fas fa-plus-circle mr-2"></i> Create Assessment
                </a>
                <a href="{{ route('lecturer.attendance') }}" class="btn btn-block btn-outline-info mb-2">
                    <i class="fas fa-clipboard-check mr-2"></i> Take Attendance
                </a>
                <a href="{{ route('lecturer.reports') }}" class="btn btn-block btn-outline-secondary">
                    <i class="fas fa-chart-pie mr-2"></i> Generate Reports
                </a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
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
            padding-left: 1.5rem;
        }
    }
</style>
@endpush
