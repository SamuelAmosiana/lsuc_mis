<x-layouts.app :title="__('My Results')">
    <div class="space-y-6">
        <!-- Results Header -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">My Academic Results</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    View your examination results and academic performance.
                </p>
            </div>
        </div>
        
        <!-- Results Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- CGPA Card -->
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Current CGPA</p>
                        <p class="text-3xl font-bold text-blue-700 dark:text-white">
                            {{ number_format($student->calculateCGPA(), 2) }}
                        </p>
                        <p class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                            Out of 5.00
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-blue-600 dark:text-blue-400">
                        <span>Academic Standing</span>
                        <span class="font-medium">
                            @php $cgpa = $student->calculateCGPA(); @endphp
                            @if($cgpa >= 4.5)
                                First Class
                            @elseif($cgpa >= 3.5)
                                Second Class Upper
                            @elseif($cgpa >= 2.5)
                                Second Class Lower
                            @elseif($cgpa >= 2.0)
                                Pass
                            @else
                                Probation
                            @endif
                        </span>
                    </div>
                    <div class="w-full bg-blue-200 dark:bg-blue-900/50 rounded-full h-2 mt-1">
                        <div class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full" style="width: {{ ($student->calculateCGPA() / 5) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Credits Card -->
            <div class="bg-green-50 dark:bg-green-900/30 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Credits Earned</p>
                        <p class="text-3xl font-bold text-green-700 dark:text-white">
                            {{ $student->completed_credits }}
                        </p>
                        <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                            Out of {{ $student->programme->total_credits ?? 120 }} required
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-green-600 dark:text-green-400">
                        <span>Progress</span>
                        <span class="font-medium">
                            {{ $student->programme ? round(($student->completed_credits / $student->programme->total_credits) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-green-200 dark:bg-green-900/50 rounded-full h-2 mt-1">
                        <div class="bg-green-600 dark:bg-green-400 h-2 rounded-full" style="width: {{ $student->programme ? ($student->completed_credits / $student->programme->total_credits) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Performance Card -->
            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Current Level</p>
                        <p class="text-3xl font-bold text-purple-700 dark:text-white">
                            Level {{ $student->level }}
                        </p>
                        <p class="mt-1 text-xs text-purple-600 dark:text-purple-400">
                            {{ $student->programme->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-purple-600 dark:text-purple-400">
                        <span>Semester GPA</span>
                        <span class="font-medium">{{ number_format($student->calculateSemesterGPA(), 2) }}</span>
                    </div>
                    <div class="w-full bg-purple-200 dark:bg-purple-900/50 rounded-full h-2 mt-1">
                        <div class="bg-purple-600 dark:bg-purple-400 h-2 rounded-full" style="width: {{ ($student->calculateSemesterGPA() / 5) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results Filter -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Examination Results</h3>
                    <div class="mt-2 md:mt-0 flex space-x-2
                    ">
                        <select id="academic_year" class="block w-full md:w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                            <option>All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>{{ $year }}/{{ $year + 1 }}</option>
                            @endforeach
                        </select>
                        <select id="semester" class="block w-full md:w-36 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                            <option value="">All Semesters</option>
                            <option value="Spring" {{ request('semester') == 'Spring' ? 'selected' : '' }}>Spring</option>
                            <option value="Fall" {{ request('semester') == 'Fall' ? 'selected' : '' }}>Fall</option>
                            <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credits</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade Point</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse($results as $result)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $result->course->code }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $result->course->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $result->course->credits }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if(in_array($result->grade, ['A', 'A+', 'A-', 'B+']))
                                            bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                        @elseif(in_array($result->grade, ['B', 'B-', 'C+', 'C']))
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300
                                        @elseif(in_array($result->grade, ['C-', 'D+', 'D']))
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300
                                        @else
                                            bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                                        @endif">
                                        {{ $result->grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $result->grade_point }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $result->status }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $result->semester }} {{ $result->academic_year }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No results found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} results
                    </div>
                    <div class="mt-2 md:mt-0">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Academic Standing -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Academic Standing</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Your academic progress and standing information.
                </p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Grading Scale</h4>
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                                <thead class="bg-gray-50 dark:bg-neutral-900">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Grade</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Points</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-800">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">A+</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">5.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">90-100%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">A</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">5.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">85-89%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">A-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">4.50</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">80-84%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">B+</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">4.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">75-79%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">B</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">3.50</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">70-74%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">B-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">3.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">65-69%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">C+</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">2.50</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">60-64%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">C</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">2.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">55-59%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">C-</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">1.50</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">50-54%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">D</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">1.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">40-49%</td>
                                    </tr>
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">F</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">0.00</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Below 40%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Academic Standing</h4>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Good Standing</h3>
                                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                        <p>Your current CGPA is {{ number_format($student->calculateCGPA(), 2) }}. You are in good academic standing.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">CGPA Requirements</h5>
                                <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Minimum CGPA to graduate: 2.00</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Academic Probation: Below 2.00 CGPA</span>
                                    </li>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Dean's List: 4.50+ CGPA</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Academic Progress</h5>
                                <div class="space-y-2">
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600 dark:text-gray-300">Credits Completed</span>
                                            <span class="font-medium">{{ $student->completed_credits }} / {{ $student->programme->total_credits ?? 120 }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full" style="width: {{ $student->programme ? ($student->completed_credits / $student->programme->total_credits) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600 dark:text-gray-300">CGPA Progress</span>
                                            <span class="font-medium">{{ number_format($student->calculateCGPA(), 2) }} / 5.00</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-green-600 dark:bg-green-400 h-2 rounded-full" style="width: {{ ($student->calculateCGPA() / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results Actions -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Transcript & Documents</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Download your academic documents.
                </p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="button" class="inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Unofficial Transcript
                    </button>
                    <button type="button" class="inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4h-6v4a2 2 0 01-2 2z" />
                        </svg>
                        Print Results Slip
                    </button>
                    <button type="button" class="inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Request Official Transcript
                    </button>
                </div>
                
                <div class="mt-6 border-t border-gray-200 dark:border-neutral-700 pt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Need Help?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        If you have any questions about your results or academic standing, please contact the Academic Affairs Office.
                    </p>
                    <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                        <a href="mailto:academics@lusakasouth.edu.zm" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            academics@lusakasouth.edu.zm
                        </a>
                        <a href="tel:+260211234567" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +260 211 234 567
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle filter form submission
            const academicYearSelect = document.getElementById('academic_year');
            const semesterSelect = document.getElementById('semester');
            
            function applyFilters() {
                const academicYear = academicYearSelect.value;
                const semester = semesterSelect.value;
                
                let url = new URL(window.location.href);
                
                if (academicYear) {
                    url.searchParams.set('academic_year', academicYear);
                } else {
                    url.searchParams.delete('academic_year');
                }
                
                if (semester) {
                    url.searchParams.set('semester', semester);
                } else {
                    url.searchParams.delete('semester');
                }
                
                window.location.href = url.toString();
            }
            
            // Add event listeners
            const filterButton = document.querySelector('button[type="button"]');
            if (filterButton) {
                filterButton.addEventListener('click', applyFilters);
            }
            
            // Handle enter key in select elements
            [academicYearSelect, semesterSelect].forEach(select => {
                select.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        applyFilters();
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>
