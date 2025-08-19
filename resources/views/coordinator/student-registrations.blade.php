@extends('components.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Student Course Registrations</h1>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search registrations...">
            </div>
            <select class="block w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option>All Status</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Rejected</option>
            </select>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Pending Approvals
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Review and approve student course registrations
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $registrations->total() }} Pending
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Course
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Programme
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registered On
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($registrations as $registration)
                    <tr class="hover:bg-gray-50" data-registration-id="{{ $registration->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                        {{ strtoupper(substr($registration->student->first_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $registration->student->first_name }} {{ $registration->student->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $registration->student->student_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $registration->course->course_code }}</div>
                            <div class="text-sm text-gray-500">{{ $registration->course->course_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->student->programme->programme_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $registration->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <button class="text-green-600 hover:text-green-900" onclick="approveRegistration({{ $registration->id }})">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button class="text-red-600 hover:text-red-900" onclick="rejectRegistration({{ $registration->id }})">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No pending registrations found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $registrations->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-medium">{{ $registrations->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-medium">{{ $registrations->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function approveRegistration(registrationId) {
    if (confirm('Are you sure you want to approve this registration?')) {
        // Show loading state
        const row = document.querySelector(`tr[data-registration-id="${registrationId}"]`);
        if (row) row.classList.add('opacity-50', 'pointer-events-none');
        
        // Make AJAX call to approve
        fetch(`/coordinator/registrations/${registrationId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'POST' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and remove the row
                showAlert('success', data.message);
                const row = document.querySelector(`tr[data-registration-id="${registrationId}"]`);
                if (row) row.remove();
                
                // Update the pending count
                updatePendingCount(-1);
            } else {
                showAlert('error', data.message || 'Failed to approve registration');
                if (row) row.classList.remove('opacity-50', 'pointer-events-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while processing your request');
            if (row) row.classList.remove('opacity-50', 'pointer-events-none');
        });
    }
}

function rejectRegistration(registrationId) {
    const reason = prompt('Please enter the reason for rejection:');
    if (reason !== null && reason.trim() !== '') {
        // Show loading state
        const row = document.querySelector(`tr[data-registration-id="${registrationId}"]`);
        if (row) row.classList.add('opacity-50', 'pointer-events-none');
        
        // Make AJAX call to reject
        fetch(`/coordinator/registrations/${registrationId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'POST',
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and remove the row
                showAlert('success', data.message);
                const row = document.querySelector(`tr[data-registration-id="${registrationId}"]`);
                if (row) row.remove();
                
                // Update the pending count
                updatePendingCount(-1);
            } else {
                showAlert('error', data.message || 'Failed to reject registration');
                if (row) row.classList.remove('opacity-50', 'pointer-events-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while processing your request');
            if (row) row.classList.remove('opacity-50', 'pointer-events-none');
        });
    } else if (reason !== null) {
        // User clicked OK but left the reason empty
        showAlert('error', 'Please provide a reason for rejection');
    }
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 p-4 rounded-md ${type === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'}`;
    alertDiv.innerHTML = `
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">
                    ${message}
                </p>
            </div>
            <div class="ml-4">
                <button type="button" class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    // Add to DOM
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function updatePendingCount(change) {
    const pendingCountElement = document.querySelector('.pending-count');
    if (pendingCountElement) {
        const currentCount = parseInt(pendingCountElement.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);
        pendingCountElement.textContent = newCount;
        
        // Update the count in the stats card if it exists
        const statsCardCount = document.querySelector('.pending-registrations-count');
        if (statsCardCount) {
            statsCardCount.textContent = newCount;
        }
    }
}

// Add CSRF token to all AJAX requests
document.addEventListener('DOMContentLoaded', function() {
    // Add registration ID to each row for easier selection
    document.querySelectorAll('tr[data-registration-id]').forEach(row => {
        const registrationId = row.getAttribute('data-registration-id');
        if (!registrationId) {
            row.setAttribute('data-registration-id', row.dataset.registrationId);
        }
    });
});
</script>
@endpush
@endsection
