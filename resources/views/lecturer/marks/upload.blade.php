@extends('layouts.lecturer')

@section('title', 'Upload Marks')

@push('styles')
<style>
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 5px;
        padding: 3rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 1.5rem;
    }
    .upload-area:hover {
        border-color: #4e73df;
        background-color: #f8f9fc;
    }
    .upload-area i {
        font-size: 3rem;
        color: #4e73df;
        margin-bottom: 1rem;
    }
    .file-info {
        margin-top: 1rem;
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload Student Marks</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upload Marks</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('lecturer.upload-marks.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="course">Select Course</label>
                            <select name="course_id" id="course" class="form-control @error('course_id') is-invalid @enderror" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $id => $name)
                                    <option value="{{ $id }}" {{ old('course_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="assessment">Assessment Type</label>
                            <select name="assessment_type" id="assessment" class="form-control @error('assessment_type') is-invalid @enderror" required>
                                <option value="">-- Select Assessment Type --</option>
                                <option value="quiz" {{ old('assessment_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                <option value="assignment" {{ old('assessment_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="midterm" {{ old('assessment_type') == 'midterm' ? 'selected' : '' }}>Midterm Exam</option>
                                <option value="final" {{ old('assessment_type') == 'final' ? 'selected' : '' }}>Final Exam</option>
                                <option value="project" {{ old('assessment_type') == 'project' ? 'selected' : '' }}>Project</option>
                                <option value="other" {{ old('assessment_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('assessment_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="max_marks">Maximum Marks</label>
                            <input type="number" name="max_marks" id="max_marks" 
                                   class="form-control @error('max_marks') is-invalid @enderror" 
                                   value="{{ old('max_marks', 100) }}" min="1" required>
                            @error('max_marks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="upload-area" id="dropArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h5>Drag & Drop your file here</h5>
                            <p class="text-muted">or click to browse files</p>
                            <input type="file" name="marks_file" id="fileInput" accept=".xlsx,.xls,.csv" style="display: none;">
                            <div class="file-info" id="fileInfo">
                                Supported formats: .xlsx, .xls, .csv
                            </div>
                            @error('marks_file')
                                <div class="text-danger mt-2">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="overwrite" id="overwrite" value="1">
                                <label class="form-check-label" for="overwrite">
                                    Overwrite existing marks for this assessment
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                <i class="fas fa-upload mr-1"></i> Upload Marks
                            </button>
                            <a href="{{ route('lecturer.results.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Results
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Instructions</h6>
                </div>
                <div class="card-body">
                    <h6>File Format Requirements:</h6>
                    <ul class="small pl-3">
                        <li>First column must contain Student ID</li>
                        <li>Second column must contain Marks</li>
                        <li>Do not include headers in the file</li>
                    </ul>
                    <hr>
                    <h6>Example:</h6>
                    <pre class="bg-light p-2 small">
STD001,85
STD002,92
STD003,78
...
                    </pre>
                    <a href="{{ asset('templates/marks_upload_template.xlsx') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download mr-1"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const uploadForm = document.getElementById('uploadForm');
        const uploadBtn = document.getElementById('uploadBtn');

        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-primary');
            dropArea.style.borderStyle = 'solid';
        }

        function unhighlight() {
            dropArea.classList.remove('border-primary');
            dropArea.style.borderStyle = 'dashed';
        }

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // Handle file selection via click
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                fileInput.files = files;
                fileInfo.innerHTML = `
                    <strong>Selected file:</strong> ${file.name}<br>
                    <span class="text-muted">${(file.size / 1024).toFixed(2)} KB</span>
                `;
            }
        }

        // Form submission
        uploadForm.addEventListener('submit', function(e) {
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Please select a file to upload.');
                return false;
            }
            
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...';
            return true;
        });
    });
</script>
@endpush
