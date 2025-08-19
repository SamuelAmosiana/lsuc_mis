@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('hr.students.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Students
        </a>
    </div>

    <!-- Student Header -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-3xl font-bold text-blue-600">
                        {{ substr($student->user->name, 0, 1) }}
                    </div>
                    <div class="ml-6
                    ">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $student->user->name }}</h2>
                        <p class="text-gray-600">{{ $student->student_id }} • {{ $student->program->name ?? 'N/A' }}</p>
                        <div class="mt-1">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-yellow-100 text-yellow-800',
                                    'suspended' => 'bg-red-100 text-red-800',
                                    'graduated' => 'bg-blue-100 text-blue-800',
                                ][$student->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="space-x-2">
                    <button onclick="showStatusModal('{{ $student->id }}', '{{ $student->status }}')" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Status
                    </button>
                    <a href="#" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="#" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    Overview
                </a>
                <a href="#academic" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    Academic
                </a>
                <a href="#documents" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    Documents
                </a>
                <a href="#attendance" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    Attendance
                </a>
                <a href="#payments" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                    Payments
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Personal Information</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->user->name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->user->email }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->phone ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->address ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($student->gender) ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Blood Group</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->blood_group ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Emergency Contact</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $student->emergency_contact_name ?? 'N/A' }}
                                @if($student->emergency_contact_relation)
                                    ({{ $student->emergency_contact_relation }})
                                @endif
                                @if($student->emergency_contact_phone)
                                    <br>{{ $student->emergency_contact_phone }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Information</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Student ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Program</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->program->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Batch Year</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->batch_year ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Admission Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : 'N/A' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Current Semester</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $student->current_semester ?? 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Status History -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Status History</h3>
                </div>
                <div class="px-4 py-5 sm:p-0">
                    <ul class="divide-y divide-gray-200">
                        @forelse($student->statusNotes as $note)
                            <li class="py-4 px-4">
                                <div class="flex space-x-3">
                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium">
                                                {{ ucfirst($note->status) }}
                                                <span class="text-xs text-gray-500">
                                                    ({{ $note->created_at->diffForHumans() }})
                                                </span>
                                            </h3>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($note->status === 'active') bg-green-100 text-green-800
                                                @elseif($note->status === 'inactive') bg-yellow-100 text-yellow-800
                                                @elseif($note->status === 'suspended') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($note->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500">
                                            {{ $note->note }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            By {{ $note->admin->name ?? 'System' }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 px-4 text-center text-sm text-gray-500">
                                No status history available
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="#" class="block px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 text-center">
                        Send Message
                    </a>
                    <a href="#" class="block px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 text-center">
                        Generate ID Card
                    </a>
                    <a href="#" class="block px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 text-center">
                        Print Transcript
                    </a>
                    <a href="#" class="block px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 text-center">
                        Deactivate Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Update Student Status</h3>
            <button onclick="document.getElementById('statusModal').classList.add('hidden')" 
                    class="text-gray-500 hover:text-gray-700">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        <form id="statusForm" action="{{ route('hr.students.update-status', $student->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="statusSelect" required
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ $student->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $student->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ $student->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="graduated" {{ $student->status === 'graduated' ? 'selected' : '' }}>Graduated</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" required
                          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Reason for status change..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('statusModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showStatusModal(studentId, currentStatus) {
        document.getElementById('statusSelect').value = currentStatus;
        document.getElementById('statusModal').classList.remove('hidden');
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('statusModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
