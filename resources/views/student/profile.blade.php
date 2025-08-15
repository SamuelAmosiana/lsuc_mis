<x-layouts.app :title="__('My Profile')">
    <div class="space-y-6">
        <!-- Profile Header -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">My Profile</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your personal information and preferences.</p>
            </div>
            
            <div class="px-6 py-6">
                <div class="md:flex md:space-x-8">
                    <!-- Profile Picture -->
                    <div class="md:w-1/4 mb-6 md:mb-0">
                        <div class="w-40 h-40 mx-auto rounded-full bg-gray-200 dark:bg-neutral-700 flex items-center justify-center overflow-hidden">
                            @if($student->profile_photo_path)
                                <img src="{{ asset('storage/' . $student->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>
                        <div class="mt-4 text-center">
                            <button type="button" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Change Photo
                            </button>
                        </div>
                    </div>
                    
                    <!-- Profile Details -->
                    <div class="md:w-3/4">
                        <form action="#" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Personal Information -->
                                <div class="col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Personal Information</h3>
                                </div>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ $student->user->name }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" disabled>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ $student->user->email }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" disabled>
                                </div>
                                
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student ID</label>
                                    <input type="text" name="student_id" id="student_id" value="{{ $student->student_id }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" disabled>
                                </div>
                                
                                <div>
                                    <label for="programme" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Programme</label>
                                    <input type="text" name="programme" id="programme" value="{{ $student->programme->name ?? 'N/A' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" disabled>
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" value="{{ $student->phone ?? '' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ $student->date_of_birth ?? '' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <!-- Contact Information -->
                                <div class="col-span-2 mt-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Contact Information</h3>
                                </div>
                                
                                <div class="col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                    <textarea name="address" id="address" rows="3" 
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">{{ $student->address ?? '' }}</textarea>
                                </div>
                                
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                                    <input type="text" name="city" id="city" value="{{ $student->city ?? '' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country</label>
                                    <input type="text" name="country" id="country" value="{{ $student->country ?? 'Zambia' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <!-- Emergency Contact -->
                                <div class="col-span-2 mt-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Emergency Contact</h3>
                                </div>
                                
                                <div>
                                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                                           value="{{ $student->emergency_contact_name ?? '' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <div>
                                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                    <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" 
                                           value="{{ $student->emergency_contact_phone ?? '' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <div class="col-span-2">
                                    <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relationship</label>
                                    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" 
                                           value="{{ $student->emergency_contact_relationship ?? '' }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="col-span-2 pt-6 border-t border-gray-200 dark:border-neutral-700">
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white dark:hover:bg-neutral-600">
                                            Cancel
                                        </button>
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
