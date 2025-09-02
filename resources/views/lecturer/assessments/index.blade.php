@extends('layouts.lecturer')

@section('title', 'My Assessments')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Assessments</h1>
        <a href="{{ route('lecturer.assessments.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Assessment
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="assessmentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Type</th>
                            <th>Due Date</th>
                            <th>Max Marks</th>
                            <th>Submissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $assessment)
                            <tr>
                                <td>{{ $assessment->title }}</td>
                                <td>{{ $assessment->course->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($assessment->type) }}</td>
                                <td>{{ $assessment->due_date->format('M d, Y') }}</td>
                                <td>{{ $assessment->max_marks }}</td>
                                <td>
                                    <span class="badge {{ $assessment->submissions_count > 0 ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $assessment->submissions_count }} / {{ $assessment->course->enrollments_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('lecturer.assessments.submissions', $assessment) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View Submissions">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">No assessments found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#assessmentsTable').DataTable({
            "pageLength": 25,
            "order": [[3, 'asc']]
        });
        
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
