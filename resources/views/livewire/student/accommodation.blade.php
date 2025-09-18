<?php

use App\Models\AccommodationAssignment;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public $assignments;
    public $currentAssignment;
    public $student;
    public $accommodationStatus;

    public function mount(): void
    {
        $this->student = Student::where('user_id', auth()->id())->first();
        $this->assignments = AccommodationAssignment::where('student_id', auth()->id())
            ->with(['room.hostel'])
            ->orderBy('year', 'desc')
            ->orderBy('term', 'desc')
            ->get();
            
        $this->currentAssignment = AccommodationAssignment::where('student_id', auth()->id())
            ->where(function($query) {
                $query->whereNull('check_out')
                      ->orWhere('check_out', '>', now());
            })
            ->with(['room.hostel'])
            ->first();
            
        $this->accommodationStatus = $this->currentAssignment ? 'assigned' : 'not_assigned';
    }
}; ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Accommodation</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View your accommodation details and history</p>
        </div>
        <div class="text-right">
            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                {{ $accommodationStatus === 'assigned' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' }}">
                {{ $accommodationStatus === 'assigned' ? 'Accommodation Assigned' : 'No Accommodation' }}
            </div>
        </div>
    </div>

    <!-- Current Accommodation -->
    @if($currentAssignment)
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Accommodation</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Your active accommodation assignment</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Hostel Information -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Hostel Details
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Hostel Name</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $currentAssignment->room->hostel->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Number</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $currentAssignment->room->room_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Capacity</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $currentAssignment->room->capacity ?? 'N/A' }} person(s)</p>
                        </div>
                    </div>
                </div>

                <!-- Assignment Period -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Assignment Period
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Academic Year</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $currentAssignment->year ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Term</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $currentAssignment->term ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-in Date</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $currentAssignment->check_in ? $currentAssignment->check_in->format('F j, Y') : 'Not checked in' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-out Date</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $currentAssignment->check_out ? $currentAssignment->check_out->format('F j, Y') : 'End of term' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status & Actions
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Active
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Assignment Date</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $currentAssignment->created_at->format('F j, Y') }}
                            </p>
                        </div>
                        <div class="pt-2">
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-neutral-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print Assignment Letter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Current Accommodation -->
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border-2 border-dashed border-gray-300 dark:border-neutral-600">
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No Active Accommodation</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">You don't currently have accommodation assigned.</p>
            <div class="mt-6">
                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Request Accommodation
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Accommodation History -->
    @if($assignments->count() > 0)
    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Accommodation History</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Your previous accommodation assignments</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Academic Year</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Term</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hostel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Room</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-in</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check-out</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($assignments as $assignment)
                    <tr class="{{ $assignment->id === $currentAssignment?->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $assignment->year ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $assignment->term ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $assignment->room->hostel->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $assignment->room->room_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $assignment->check_in ? $assignment->check_in->format('M j, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $assignment->check_out ? $assignment->check_out->format('M j, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($assignment->id === $currentAssignment?->id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Current
                                </span>
                            @elseif($assignment->check_out && $assignment->check_out < now())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                    Active
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Application Information -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Accommodation Information</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <p>• Accommodation assignments are made by the Enrollment Office</p>
                    <p>• Contact the Enrollment Office for accommodation requests or changes</p>
                    <p>• Room assignments are subject to availability and college policies</p>
                    <p>• Check-in and check-out procedures must be followed</p>
                </div>
            </div>
        </div>
    </div>
</div>


