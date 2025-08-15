<x-layouts.app :title="__('My Accommodation')">
    <div class="space-y-6">
        <!-- Accommodation Header -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">My Accommodation</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage your hostel accommodation for the {{ now()->year }} academic year.
                </p>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Accommodation Status -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Accommodation Status</h3>
            </div>
            
            <div class="p-6">
                @if($status === 'assigned' && $accommodation)
                    <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm text-green-700 dark:text-green-300">
                                    <span class="font-medium">Accommodation Assigned</span>
                                </p>
                                <p class="mt-3 text-sm md:mt-0 md:ml-6">
                                    <a href="#details" class="whitespace-nowrap font-medium text-green-700 dark:text-green-300 hover:text-green-600 dark:hover:text-green-400">
                                        View details <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="details" class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-neutral-900/50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Room Details</h4>
                            <dl class="space-y-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hostel</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->room->hostel->name }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->room->room_number }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->room->type }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Floor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->room->floor }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rent</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">ZMW {{ number_format($accommodation->room->rent, 2) }} per semester</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-neutral-900/50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Assignment Details</h4>
                            <dl class="space-y-4">
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Assignment Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->assigned_at->format('F j, Y') }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                            Active
                                        </span>
                                    </dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Academic Year</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->academic_year }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Semester</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $accommodation->semester }}</dd>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Check-out Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                        {{ $accommodation->check_out_date ? $accommodation->check_out_date->format('F j, Y') : 'End of Semester' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-8 border-t border-gray-200 dark:border-neutral-700 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Roommates</h4>
                        @if($accommodation->room->occupants->count() > 1)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                    <thead class="bg-gray-50 dark:bg-neutral-900">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Programme</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                                        @foreach($accommodation->room->occupants as $occupant)
                                            @if($occupant->id !== $student->id)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $occupant->user->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $occupant->programme->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $occupant->phone ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">You are the only occupant in this room.</p>
                        @endif
                    </div>
                    
                    <div class="mt-8 border-t border-gray-200 dark:border-neutral-700 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Accommodation Rules</h4>
                        <ul class="list-disc pl-5 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <li>Keep your room clean and tidy at all times.</li>
                            <li>No unauthorized guests are allowed in the hostel after 10:00 PM.</li>
                            <li>Report any maintenance issues to the hostel warden immediately.</li>
                            <li>No cooking in the rooms - use the designated kitchen areas.</li>
                            <li>Noise should be kept to a minimum, especially during study hours and after 11:00 PM.</li>
                            <li>Smoking and alcohol are strictly prohibited in the hostel premises.</li>
                        </ul>
                        
                        <div class="mt-6">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download Rules PDF
                            </button>
                        </div>
                    </div>
                    
                @elseif($status === 'pending')
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Application Pending Review</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>Your accommodation application is currently being processed. You will be notified once a decision has been made.</p>
                                </div>
                                <div class="mt-4">
                                    <div class="text-sm text-blue-700 dark:text-blue-300">
                                        <p class="font-medium">Application Details:</p>
                                        <ul class="list-disc pl-5 mt-1 space-y-1">
                                            <li>Applied on: {{ $student->accommodationApplication->created_at->format('F j, Y') }}</li>
                                            <li>Status: Under Review</li>
                                            <li>Academic Year: {{ $student->accommodationApplication->academic_year }}</li>
                                            <li>Semester: {{ $student->accommodationApplication->semester }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-gray-50 dark:bg-neutral-900/50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Application Notes</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $student->accommodationApplication->notes ?? 'No additional notes provided.' }}
                        </p>
                        
                        <div class="mt-6">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    x-data="{}"
                                    @click="if(confirm('Are you sure you want to cancel your accommodation application?')) { 
                                        $wire.cancelApplication();
                                    }">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Cancel Application
                            </button>
                        </div>
                    </div>
                    
                @else
                    <div class="rounded-lg bg-amber-50 dark:bg-amber-900/20 p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">No Accommodation Assigned</h3>
                                <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                    <p>You have not been assigned any accommodation for the current academic year. Please apply for accommodation using the form below.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Application Form -->
                    <div class="mt-8 sm:mt-10">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Accommodation Application</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Fill out the form to apply for hostel accommodation.
                                </p>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <form action="{{ route('student.accommodation.apply') }}" method="POST">
                                    @csrf
                                    <div class="shadow overflow-hidden sm:rounded-md">
                                        <div class="px-4 py-5 bg-white dark:bg-neutral-800 sm:p-6">
                                            <div class="grid grid-cols-6 gap-6">
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Academic Year</label>
                                                    <select id="academic_year" name="academic_year" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:text-white">
                                                        <option value="{{ now()->year }}">{{ now()->year }} / {{ now()->addYear()->year }}</option>
                                                        <option value="{{ now()->addYear()->year }}">{{ now()->addYear()->year }} / {{ now()->addYears(2)->year }}</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Semester</label>
                                                    <select id="semester" name="semester" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:text-white">
                                                        <option value="Spring">Spring</option>
                                                        <option value="Fall">Fall</option>
                                                        <option value="Full Year">Full Year</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-span-6">
                                                    <label for="preferred_hostel" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Hostel (Optional)</label>
                                                    <select id="preferred_hostel" name="preferred_hostel" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:text-white">
                                                        <option value="">No Preference</option>
                                                        <option value="Male Hostel">Male Hostel</option>
                                                        <option value="Female Hostel">Female Hostel</option>
                                                        <option value="Postgraduate Hostel">Postgraduate Hostel</option>
                                                        <option value="International Students Hostel">International Students Hostel</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-span-6">
                                                    <label for="room_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Room Type</label>
                                                    <select id="room_type" name="room_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:text-white">
                                                        <option value="Single">Single Occupancy</option>
                                                        <option value="Double">Double Occupancy</option>
                                                        <option value="Shared">Shared (4-6 persons)</option>
                                                        <option value="No Preference">No Preference</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-span-6">
                                                    <label for="special_needs" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Special Needs/Requirements</label>
                                                    <textarea id="special_needs" name="special_needs" rows="3" class="mt-1 block w-full border border-gray-300 dark:border-neutral-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-neutral-700 dark:text-white" placeholder="Please specify any special requirements or needs you may have (e.g., medical conditions, accessibility needs, etc.)"></textarea>
                                                </div>
                                                
                                                <div class="col-span-6">
                                                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Information</label>
                                                    <input type="text" name="emergency_contact" id="emergency_contact" class="mt-1 block w-full border border-gray-300 dark:border-neutral-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-neutral-700 dark:text-white" placeholder="Name and Phone Number">
                                                </div>
                                                
                                                <div class="col-span-6">
                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                                            <input id="terms" name="terms" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" required>
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">I agree to the terms and conditions</label>
                                                            <p class="text-gray-500 dark:text-gray-400">By submitting this application, I agree to abide by the hostel rules and regulations.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 bg-gray-50 dark:bg-neutral-900 text-right sm:px-6">
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Submit Application
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Important Dates -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Important Dates</h3>
            </div>
            <div class="p-6">
                <div class="overflow-hidden
                ">
                    <ul class="divide-y divide-gray-200 dark:divide-neutral-700">
                        <li class="py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Accommodation Application Opens</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">January 15, {{ now()->year }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Accommodation Application Deadline</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">February 15, {{ now()->year }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Accommodation Assignment Notifications</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">March 1, {{ now()->year }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Move-in Date</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">March 15, {{ now()->year }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Need Help?</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Hostel Office</h4>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <p>Main Campus, Administration Block</p>
                            <p>Ground Floor, Room G12</p>
                            <p class="mt-2"><span class="font-medium">Email:</span> hostel@lusakasouth.edu.zm</p>
                            <p><span class="font-medium">Phone:</span> +260 211 234 567</p>
                            <p class="mt-2"><span class="font-medium">Office Hours:</span> Mon-Fri, 08:00 - 16:30</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Hostel Warden</h4>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <p>Mr. John Banda</p>
                            <p class="mt-2"><span class="font-medium">Email:</span> warden@lusakasouth.edu.zm</p>
                            <p><span class="font-medium">Phone:</span> +260 977 123 456</p>
                            <p class="mt-2"><span class="font-medium">Office Hours:</span> Mon, Wed, Fri, 10:00 - 14:00</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Frequently Asked Questions</h4>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.94 6.94a.75.75 0 11-1.061-1.061 3 3 0 112.871 5.026v.345a.75.75 0 01-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1010.5 6H13a.75.75 0 010 1.5h-2.83l.245.166a.75.75 0 01.245.555v.5a.75.75 0 01-1.5 0v-.197a.25.25 0 01.119-.213l1.5-1a.75.75 0 01.832 0zM10 13a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            How are room assignments made?
                        </a>
                        <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.94 6.94a.75.75 0 11-1.061-1.061 3 3 0 112.871 5.026v.345a.75.75 0 01-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1010.5 6H13a.75.75 0 010 1.5h-2.83l.245.166a.75.75 0 01.245.555v.5a.75.75 0 01-1.5 0v-.197a.25.25 0 01.119-.213l1.5-1a.75.75 0 01.832 0zM10 13a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            What should I bring with me to the hostel?
                        </a>
                        <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.94 6.94a.75.75 0 11-1.061-1.061 3 3 0 112.871 5.026v.345a.75.75 0 01-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1010.5 6H13a.75.75 0 010 1.5h-2.83l.245.166a.75.75 0 01.245.555v.5a.75.75 0 01-1.5 0v-.197a.25.25 0 01.119-.213l1.5-1a.75.75 0 01.832 0zM10 13a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            What are the hostel rules and regulations?
                        </a>
                        <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.94 6.94a.75.75 0 11-1.061-1.061 3 3 0 112.871 5.026v.345a.75.75 0 01-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1010.5 6H13a.75.75 0 010 1.5h-2.83l.245.166a.75.75 0 01.245.555v.5a.75.75 0 01-1.5 0v-.197a.25.25 0 01.119-.213l1.5-1a.75.75 0 01.832 0zM10 13a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            How do I pay for accommodation?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
