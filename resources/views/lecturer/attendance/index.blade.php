@extends('layouts.lecturer')

@section('title', 'Attendance Management')

@push('styles')
<style>
    .attendance-mark {
        cursor: pointer;
        transition: all 0.3s;
    }
    .attendance-mark:hover {
        transform: scale(1.1);
    }
    .present { background-color: #d4edda !important; }
    .absent { background-color: #f8d7da !important; }
    .late { background-color: #fff3cd !important; }
    .excused { background-color: #e2e3e5 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Attendance Management</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                @if($selectedCourse)
                    {{ $selectedCourse->name }} - {{ $selectedCourse->code }}
                @else
                    Select a course to take attendance
                @endif
            </h6>
            <div>
                <form action="{{ route('lecturer.attendance.index') }}" method="GET" class="form-inline">
                    <div class="form-group mr-2 mb-0">
                        <select name="course" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $id => $name)
                                <option value="{{ $id }}" {{ request('course') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedCourse)
                        <input type="date" name="date" class="form-control form-control-sm mr-2" 
                               value="{{ request('date', now()->format('Y-m-d')) }}" onchange="this.form.submit()">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-sync-alt"></i> Load
                        </button>
                    @endif
                </form>
            </div>
        </div>
        
        @if($selectedCourse)
            <div class="card-body">
                <form action="{{ route('lecturer.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                    <input type="hidden" name="date" value="{{ request('date', now()->format('Y-m-d')) }}">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Excused</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedCourse->enrollments as $enrollment)
                                    @php
                                        $attendance = $enrollment->student->attendance
                                            ->where('course_id', $selectedCourse->id)
                                            ->where('date', request('date', now()->format('Y-m-d')))
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $enrollment->student->student_id }}</td>
                                        <td>{{ $enrollment->student->name }}</td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input attendance-status" type="radio" 
                                                       name="attendance[{{ $enrollment->student_id }}]" 
                                                       value="present" 
                                                       {{ ($attendance->status ?? '') == 'present' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input attendance-status" type="radio" 
                                                       name="attendance[{{ $enrollment->student_id }}]" 
                                                       value="absent" 
                                                       {{ ($attendance->status ?? '') == 'absent' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input attendance-status" type="radio" 
                                                       name="attendance[{{ $enrollment->student_id }}]" 
                                                       value="late" 
                                                       {{ ($attendance->status ?? '') == 'late' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input attendance-status" type="radio" 
                                                       name="attendance[{{ $enrollment->student_id }}]" 
                                                       value="excused" 
                                                       {{ ($attendance->status ?? '') == 'excused' ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="notes[{{ $enrollment->student_id }}]" 
                                                   value="{{ $attendance->notes ?? '' }}" 
                                                   placeholder="Notes">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Attendance
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="card-body text-center py-5">
                <div class="text-muted mb-3">
                    <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                    <h4>No course selected</h4>
                    <p>Please select a course to take attendance.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Highlight row based on attendance status
        $('.attendance-status').change(function() {
            var row = $(this).closest('tr');
            row.removeClass('present absent late excused');
            
            if ($(this).is(':checked')) {
                row.addClass($(this).val());
            }
        });
        
        // Initialize DataTable
        if ($.fn.DataTable.isDataTable('#attendanceTable')) {
            $('#attendanceTable').DataTable().destroy();
        }
        
        $('#attendanceTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "columnDefs": [
                { "orderable": false, "targets": [2,3,4,5,6] },
                { "searchable": false, "targets": [2,3,4,5] }
            ]
        });
        
        // Apply initial highlights
        $('.attendance-status:checked').each(function() {
            $(this).closest('tr').addClass($(this).val());
        });
    });
</script>
@endpush
