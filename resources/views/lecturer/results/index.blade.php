@extends('layouts.lecturer')

@section('title', 'Student Results')

@push('styles')
<style>
    .result-row {
        transition: background-color 0.3s;
    }
    .result-row:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Results</h1>
        <div>
            <a href="{{ route('lecturer.upload-marks') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-upload fa-sm text-white-50"></i> Upload Marks
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Course Results</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="resultsTable" width="100%" cellspacing="0">
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
                            <tr class="result-row">
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->program->name ?? 'N/A' }}</td>
                                <td>{{ $course->semester }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $course->enrollments_count }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('lecturer.courses.show', $course) }}#results" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View Results">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lecturer.upload-marks') }}?course={{ $course->id }}" class="btn btn-sm btn-success" data-toggle="tooltip" title="Upload Marks">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                    <button class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Export Results">
                                        <i class="fas fa-file-export"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">No courses found with results.</div>
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
        $('#resultsTable').DataTable({
            "pageLength": 25,
            "order": [[0, 'asc']],
            "columnDefs": [
                { "orderable": false, "targets": [5] }
            ]
        });
        
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
