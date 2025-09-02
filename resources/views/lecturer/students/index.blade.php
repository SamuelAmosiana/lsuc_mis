@extends('layouts.lecturer')

@section('title', 'Students')

@push('styles')
<style>
    .student-card {
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .student-avatar {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students</h1>
        <div class="d-flex">
            <form action="{{ route('lecturer.students.index') }}" method="GET" class="form-inline mr-2">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" 
                           placeholder="Search students..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
            <button class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                <i class="fas fa-filter fa-sm text-white-50"></i> Filter
            </button>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Students</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('lecturer.students.index') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="course">Course</label>
                            <select name="course" id="course" class="form-control">
                                <option value="">All Courses</option>
                                @foreach($courses as $id => $name)
                                    <option value="{{ $id }}" {{ request('course') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="program">Program</label>
                            <select name="program" id="program" class="form-control">
                                <option value="">All Programs</option>
                                @foreach($programs as $id => $name)
                                    <option value="{{ $id }}" {{ request('program') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        @if(request()->has('course') || request()->has('program') || request()->has('search'))
                            <a href="{{ route('lecturer.students.index') }}" class="btn btn-link text-danger">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($students as $student)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 student-card" 
                     onclick="window.location.href='{{ route('lecturer.students.show', $student) }}'">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <img src="{{ $student->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=random' }}" 
                                     alt="{{ $student->name }}" class="student-avatar rounded-circle">
                            </div>
                            <div class="col ml-3">
                                <div class="h5 font-weight-bold text-primary mb-1">{{ $student->name }}</div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                    {{ $student->student_id }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $student->program->name ?? 'No Program' }}
                                </div>
                                <div class="mt-2">
                                    @foreach($student->courses->take(2) as $course)
                                        <span class="badge badge-info mr-1">{{ $course->code }}</span>
                                    @endforeach
                                    @if($student->courses->count() > 2)
                                        <span class="badge badge-secondary">+{{ $student->courses->count() - 2 }} more</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                        <h4>No students found</h4>
                        <p>Try adjusting your search or filter criteria</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($students->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $students->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
