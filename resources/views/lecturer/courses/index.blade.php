@extends('layouts.lecturer')

@section('title', 'My Courses')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Courses</h1>
    </div>

    <!-- Courses List -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="coursesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Program</th>
                            <th>Semester</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->program->name ?? 'N/A' }}</td>
                                <td>{{ $course->semester }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $course->enrollments_count }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('lecturer.courses.show', $course) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lecturer.attendance.index') }}?course={{ $course->id }}" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Attendance">
                                        <i class="fas fa-clipboard-check"></i>
                                    </a>
                                    <a href="{{ route('lecturer.upload-marks') }}?course={{ $course->id }}" class="btn btn-sm btn-success" data-toggle="tooltip" title="Upload Marks">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">No courses assigned yet.</div>
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
        $('#coursesTable').DataTable({
            "pageLength": 25,
            "order": [[0, 'asc']]
        });
        
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
