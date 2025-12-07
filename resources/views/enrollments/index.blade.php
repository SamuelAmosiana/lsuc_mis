 m@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1>Enrollment Management</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Enrollment
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('enrollments.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach(['pending', 'approved', 'rejected', 'waitlisted'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Enrollment Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="physical" {{ request('type') == 'physical' ? 'selected' : '' }}>Physical</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="needsInterview" name="needs_interview" 
                               {{ request()->has('needs_interview') ? 'checked' : '' }}>
                        <label class="form-check-label" for="needsInterview">Needs Interview</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="needsAccommodation" name="needs_accommodation"
                               {{ request()->has('needs_accommodation') ? 'checked' : '' }}>
                        <label class="form-check-label" for="needsAccommodation">Needs Accommodation</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Program</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Accommodation</th>
                            <th>Interview</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->name }}</td>
                                <td>{{ $enrollment->program->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $enrollment->enrollment_type === 'online' ? 'info' : 'primary' }}">
                                        {{ ucfirst($enrollment->enrollment_type) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'waitlisted' => 'info'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$enrollment->status] ?? 'secondary' }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($enrollment->needs_accommodation)
                                        <span class="badge bg-{{ $enrollment->accommodation_status === 'assigned' ? 'success' : 'warning' }}">
                                            {{ ucfirst(str_replace('_', ' ', $enrollment->accommodation_status)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not needed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->interview_date)
                                        {{ $enrollment->interview_date->format('M d, Y') }}
                                        {{ $enrollment->interview_time ? ' at ' . \Carbon\Carbon::parse($enrollment->interview_time)->format('h:i A') : '' }}
                                    @else
                                        <span class="text-warning">Not scheduled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('enrollments.show', $enrollment) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('enrollments.edit', $enrollment) }}" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$enrollment->interview_date)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info schedule-interview" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#scheduleInterviewModal"
                                                    data-enrollment-id="{{ $enrollment->id }}"
                                                    title="Schedule Interview">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No enrollments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($enrollments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $enrollments->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="scheduleInterviewForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Interview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="interviewDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="interviewDate" name="interview_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="interviewTime" class="form-label">Time</label>
                        <input type="time" class="form-control" id="interviewTime" name="interview_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="interviewNotes" class="form-label">Notes</label>
                        <textarea class="form-control" id="interviewNotes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Interview</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle schedule interview form submission
        const scheduleInterviewModal = document.getElementById('scheduleInterviewModal');
        if (scheduleInterviewModal) {
            scheduleInterviewModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const enrollmentId = button.getAttribute('data-enrollment-id');
                const form = document.getElementById('scheduleInterviewForm');
                form.action = `/enrollments/${enrollmentId}/schedule-interview`;
                
                // Reset form
                form.reset();
            });
        }
    });
</script>
@endpush
@endsection
