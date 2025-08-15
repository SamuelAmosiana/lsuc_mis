<x-layouts.app :title="__('My Docket')">
    <div class="space-y-6">
        <!-- Docket Header -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">My Academic Docket</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Download and print your academic documents.
                </p>
            </div>
        </div>
        
        <!-- Docket Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Academic Documents -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Academic Documents</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Unofficial Transcript</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • Updated {{ now()->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'transcript']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-green-100 dark:bg-green-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Results Slip</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • {{ now()->format('M Y') }} Semester</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'results']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h.01M15 10h.01M12 10h.01M12 15h.01M9 15h.01M15 15h.01M12 20h.01M9 20h.01M15 20h.01M12 4a8 8 0 100 16 8 8 0 000-16z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Proof of Enrollment</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • Valid until {{ now()->addMonths(3)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'enrollment']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-neutral-900 text-right text-xs text-gray-500 dark:text-gray-400">
                    Documents are digitally signed and verifiable
                </div>
            </div>
            
            <!-- Registration Documents -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Registration</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-indigo-100 dark:bg-indigo-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Registration Form</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • {{ now()->format('Y') }} Academic Year</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'registration']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-amber-100 dark:bg-amber-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Course Registration</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • {{ now()->format('M Y') }} Semester</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'courses']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Student ID Card</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG • Valid until {{ now()->addYear()->format('M Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'id']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-neutral-900 text-right text-xs text-gray-500 dark:text-gray-400">
                    Always carry your student ID card with you
                </div>
            </div>
            
            <!-- Financial Documents -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Financial Records</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-rose-100 dark:bg-rose-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Fee Statement</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • {{ now()->format('M Y') }} Statement</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'fee-statement']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-cyan-50 dark:bg-cyan-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-cyan-100 dark:bg-cyan-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h2m-6 4h4m6 0h4m-6-4h-4m6 0h-4" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Payment Receipts</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ZIP • All receipts ({{ now()->format('Y') }})</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'receipts']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-violet-50 dark:bg-violet-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 rounded-full bg-violet-100 dark:bg-violet-900/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21l7-7m0 0l-7-7m7 7H3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Sponsorship Letter</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF • Template for sponsors</p>
                            </div>
                        </div>
                        <a href="{{ route('student.docket.download', ['type' => 'sponsorship']) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-neutral-900 text-right text-xs text-gray-500 dark:text-gray-400">
                    Keep financial records for your reference
                </div>
            </div>
        </div>
        
        <!-- Document Requests -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Request Official Documents</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Request official certified copies of your academic records.
                </p>
            </div>
            <div class="p-6">
                <form action="{{ route('student.docket.request') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="document_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Document Type</label>
                            <select id="document_type" name="document_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                <option value="transcript">Official Transcript</option>
                                <option value="diploma">Diploma Certificate</option>
                                <option value="enrollment">Enrollment Verification</option>
                                <option value="degree">Degree Certificate</option>
                                <option value="other">Other (Specify in notes)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Purpose</label>
                            <select id="purpose" name="purpose" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                <option value="employment">Employment</option>
                                <option value="further_studies">Further Studies</option>
                                <option value="scholarship">Scholarship Application</option>
                                <option value="immigration">Immigration</option>
                                <option value="personal">Personal Use</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="delivery_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Delivery Method</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex items-center">
                                    <input id="email_delivery" name="delivery_method" type="radio" value="email" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:bg-neutral-700 dark:border-neutral-600" checked>
                                    <label for="email_delivery" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email (Digital Copy - Free)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="postal_delivery" name="delivery_method" type="radio" value="post" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:bg-neutral-700 dark:border-neutral-600">
                                    <label for="postal_delivery" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Postal Mail (Hard Copy - K50.00)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="pickup_delivery" name="delivery_method" type="radio" value="pickup" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:bg-neutral-700 dark:border-neutral-600">
                                    <label for="pickup_delivery" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Pickup from Registry (Hard Copy - K25.00)
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Special Instructions (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-neutral-700 dark:border-neutral-600 dark:text-white"></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="declaration" name="declaration" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded dark:bg-neutral-700 dark:border-neutral-600" required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="declaration" class="font-medium text-gray-700 dark:text-gray-300">I declare that the information provided is accurate</label>
                                    <p class="text-gray-500 dark:text-gray-400">I understand that false information may result in the rejection of my request.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white dark:hover:bg-neutral-600">
                            Reset
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Document History -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Document Request History</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    View the status of your previous document requests.
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Request #</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Document</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requested</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#DOC-2023-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Official Transcript</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Jun 15, 2023</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Download</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#DOC-2023-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Degree Certificate</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Jul 5, 2023</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                    Processing
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="text-gray-400 dark:text-gray-500">Not available</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#DOC-2023-003</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Enrollment Verification</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Jul 20, 2023</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                    Payment Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Pay Now</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700 text-right">
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                    View all requests
                </a>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 14.25v-4.5a2.25 2.25 0 00-4.5 0v4.5a2.25 2.25 0 004.5 0v-1.5a2.25 2.25 0 00-2.25-2.25h-6a2.25 2.25 0 00-2.25 2.25v1.5a2.25 2.25 0 004.5 0v-4.5a2.25 2.25 0 00-4.5 0v4.5a2.25 2.25 0 004.5 0V14.25z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Need help with your documents?</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>If you have any questions about your academic documents or need assistance with your requests, please contact the Academic Registry Office.</p>
                        <div class="mt-4 space-y-1">
                            <p class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:registry@lusakasouth.edu.zm" class="hover:underline">registry@lusakasouth.edu.zm</a>
                            </p>
                            <p class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:+260211234568" class="hover:underline">+260 211 234 568</a>
                            </p>
                            <p class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-7.071-7.071 7.071-7.071a1.998 1.998 0 012.827 0L21.44 6.34a5 5 0 00-8.932-8.932V13a2 2 0 001.103 3.754A5.002 5.002 0 0113 21v-4a2 2 0 00-2-2 50.002 50.002 0 0036 0 27.414 27.414 0 005.657 33.276L27.828 25.475a2 2 0 002.121-2.121v-.003z" />
                                </svg>
                                <span>Academic Registry Office, Main Administration Building</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
