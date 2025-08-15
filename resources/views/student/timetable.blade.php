<x-layouts.app :title="__('My Timetable')">
    <div class="space-y-6">
        <!-- Timetable Header -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">My Class Timetable</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            View your weekly class schedule for {{ $currentSemester }} {{ $academicYear }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex space-x-3
                    ">
                        <select id="semester-select" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $semester->id === $currentSemesterId ? 'selected' : '' }}>
                                    {{ $semester->name }} {{ $semester->academic_year }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H9v4a2 2 0 002 2z" />
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timetable View Toggle -->
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200 dark:border-neutral-700">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button type="button" id="weekly-tab" class="timetable-tab active" data-view="weekly">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Weekly View</span>
                    </button>
                    <button type="button" id="list-tab" class="timetable-tab" data-view="list">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <span>List View</span>
                    </button>
                    <button type="button" id="calendar-tab" class="timetable-tab" data-view="calendar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Calendar View</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Weekly Timetable -->
        <div id="weekly-view" class="timetable-view">
            <div class="bg-white dark:bg-neutral-800 shadow overflow-hidden rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">Time</th>
                                @foreach($days as $day)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ $day }}
                                        <div class="text-xs font-normal text-gray-400 dark:text-gray-500">
                                            {{ now()->startOf('week')->addDay(array_search($day, $days))->format('M j') }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                            @foreach($timeSlots as $time => $slot)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white w-32">
                                        {{ $slot }}
                                    </td>
                                    @foreach($days as $dayIndex => $day)
                                        @php
                                            $hasClass = false;
                                            $classes = [];
                                            foreach($timetable as $class) {
                                                if ($class['day'] === $day && $class['start_time'] === $time) {
                                                    $hasClass = true;
                                                    $classes[] = $class;
                                                }
                                            }
                                        @endphp
                                        <td class="px-6 py-4 {{ $hasClass ? 'bg-gray-50 dark:bg-neutral-900' : '' }}">
                                            @foreach($classes as $class)
                                                <div class="mb-2 p-3 rounded-lg border-l-4 {{ $class['type'] === 'lecture' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' }}">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-medium {{ $class['type'] === 'lecture' ? 'text-blue-800 dark:text-blue-200' : 'text-purple-800 dark:text-purple-200' }}">
                                                            {{ strtoupper($class['type']) }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white dark:bg-neutral-700 text-gray-800 dark:text-gray-200">
                                                            {{ $class['room'] }}
                                                        </span>
                                                    </div>
                                                    <h4 class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $class['course_code'] }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $class['title'] }}
                                                    </p>
                                                    <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        {{ $class['lecturer'] }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div id="list-view" class="timetable-view hidden">
            <div class="bg-white dark:bg-neutral-800 shadow overflow-hidden rounded-lg">
                <ul class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($timetable as $class)
                        <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-neutral-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center {{ $class['type'] === 'lecture' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-300' }}">
                                        @if($class['type'] === 'lecture')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $class['course_code'] }} - {{ $class['title'] }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $class['day'] }}, {{ $class['start_time'] }} - {{ $class['end_time'] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $class['room'] }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $class['lecturer'] }}</div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Calendar View -->
        <div id="calendar-view" class="timetable-view hidden">
            <div class="bg-white dark:bg-neutral-800 shadow rounded-lg overflow-hidden">
                <div id="calendar" class="p-4">
                    <!-- Calendar will be rendered here by FullCalendar JS -->
                </div>
            </div>
        </div>

        <!-- Exam Schedule -->
        <div class="bg-white dark:bg-neutral-800 shadow overflow-hidden rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Exam Schedule</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Upcoming examinations for your registered courses
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Venue</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse($exams as $exam)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $exam['course_code'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $exam['title'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $exam['date']->format('D, M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $exam['start_time'] }} - {{ $exam['end_time'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $exam['venue'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $exam['status'] === 'upcoming' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 
                                           ($exam['status'] === 'in_progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' : 
                                           'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300') }}">
                                        {{ str_replace('_', ' ', ucfirst($exam['status'])) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No exam schedule available for the current semester.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($timetable as $class)
                    {
                        title: '{{ $class['course_code'] }} - {{ $class['type'] === 'lecture' ? 'Lecture' : 'Tutorial' }}',
                        start: '{{ now()->startOf('week')->addDay(array_search($class['day'], $days))->format('Y-m-d') }}T{{ $class['start_time'] }}',
                        end: '{{ now()->startOf('week')->addDay(array_search($class['day'], $days))->format('Y-m-d') }}T{{ $class['end_time'] }}',
                        backgroundColor: '{{ $class['type'] === 'lecture' ? '#3b82f6' : '#8b5cf6' }}',
                        borderColor: '{{ $class['type'] === 'lecture' ? '#3b82f6' : '#8b5cf6' }}',
                        extendedProps: {
                            description: '{{ $class['title'] }}',
                            lecturer: '{{ $class['lecturer'] }}',
                            room: '{{ $class['room'] }}'
                        }
                    },
                    @endforeach
                    @foreach($exams as $exam)
                    {
                        title: '{{ $exam['course_code'] }} - Exam',
                        start: '{{ $exam['date']->format('Y-m-d') }}T{{ $exam['start_time'] }}',
                        end: '{{ $exam['date']->format('Y-m-d') }}T{{ $exam['end_time'] }}',
                        backgroundColor: '#ef4444',
                        borderColor: '#ef4444',
                        extendedProps: {
                            description: '{{ $exam['title'] }}',
                            venue: '{{ $exam['venue'] }}',
                            type: 'exam'
                        }
                    },
                    @endforeach
                ],
                eventContent: function(arg) {
                    let titleEl = document.createElement('div');
                    titleEl.classList.add('fc-event-title');
                    titleEl.innerHTML = arg.event.title;
                    
                    let timeEl = document.createElement('div');
                    timeEl.classList.add('fc-event-time');
                    timeEl.innerHTML = arg.timeText;
                    
                    let container = document.createElement('div');
                    container.classList.add('fc-event-main-frame');
                    container.appendChild(timeEl);
                    container.appendChild(titleEl);
                    
                    let arrayOfDomNodes = [container];
                    return { domNodes: arrayOfDomNodes };
                },
                eventDidMount: function(info) {
                    // Add tooltip with more information
                    if (info.event.extendedProps.description) {
                        tippy(info.el, {
                            content: `
                                <div class="text-sm">
                                    <div class="font-medium">${info.event.title}</div>
                                    <div class="text-gray-600 dark:text-gray-300">${info.event.extendedProps.description}</div>
                                    ${info.event.extendedProps.lecturer ? 
                                        `<div class="mt-1 flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">${info.event.extendedProps.lecturer}</span>
                                        </div>` : ''
                                    }
                                    ${info.event.extendedProps.room || info.event.extendedProps.venue ? 
                                        `<div class="mt-1 flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">${info.event.extendedProps.room || info.event.extendedProps.venue}</span>
                                        </div>` : ''
                                    }
                                </div>
                            `,
                            allowHTML: true,
                            theme: 'light',
                            placement: 'top',
                            interactive: true,
                            appendTo: document.body
                        });
                    }
                },
                height: 'auto',
                nowIndicator: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '21:00:00',
                allDaySlot: false,
                displayEventTime: true,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                },
                firstDay: 1, // Start week on Monday
                dayHeaderFormat: { weekday: 'short', month: 'short', day: 'numeric', omitCommas: true },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    day: 'Day'
                }
            });
            
            calendar.render();

            // Tab switching functionality
            const tabs = document.querySelectorAll('.timetable-tab');
            const views = document.querySelectorAll('.timetable-view');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400'));
                    this.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                    
                    // Show selected view
                    const view = this.getAttribute('data-view');
                    views.forEach(v => v.classList.add('hidden'));
                    document.getElementById(`${view}-view`).classList.remove('hidden');
                    
                    // Update calendar size when switching to calendar view
                    if (view === 'calendar') {
                        setTimeout(() => {
                            calendar.updateSize();
                        }, 10);
                    }
                });
            });

            // Semester selector
            document.getElementById('semester-select').addEventListener('change', function() {
                // In a real app, this would fetch the timetable for the selected semester
                // For now, we'll just show a loading state
                const selectedSemester = this.options[this.selectedIndex].text;
                alert(`Loading timetable for ${selectedSemester}...`);
                // Here you would typically make an AJAX request to fetch the new timetable data
                // and update the view accordingly
            });
        });
    </script>
    <style>
        .timetable-tab {
            @apply px-4 py-3 text-sm font-medium text-center border-b-2 border-transparent flex items-center justify-center cursor-pointer transition-colors duration-150;
        }
        .timetable-tab:hover {
            @apply text-gray-700 border-gray-300 dark:text-gray-200 dark:border-gray-600;
        }
        .timetable-tab.active {
            @apply border-blue-500 text-blue-600 dark:text-blue-400;
        }
        .fc {
            @apply text-gray-800 dark:text-gray-200;
        }
        .fc .fc-toolbar-title {
            @apply text-lg font-semibold text-gray-900 dark:text-white;
        }
        .fc .fc-button {
            @apply bg-white border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white dark:hover:bg-neutral-600;
        }
        .fc .fc-button-primary:disabled {
            @apply bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-500;
        }
        .fc .fc-button-active {
            @apply bg-blue-50 border-blue-300 text-blue-700 dark:bg-blue-900/30 dark:border-blue-700 dark:text-blue-300;
        }
        .fc .fc-daygrid-day.fc-day-today {
            @apply bg-blue-50 dark:bg-blue-900/20;
        }
        .fc .fc-col-header-cell-cushion {
            @apply text-gray-900 dark:text-white;
        }
        .fc .fc-daygrid-day-number {
            @apply text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400;
        }
        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            @apply font-bold text-blue-600 dark:text-blue-400;
        }
        .fc .fc-event {
            @apply border-0 rounded-md p-1 text-xs font-medium cursor-pointer;
        }
        .fc .fc-event .fc-event-main {
            @apply p-1;
        }
        .fc .fc-event .fc-event-title {
            @apply font-normal;
        }
        .fc .fc-event .fc-event-time {
            @apply font-semibold;
        }
        .fc .fc-timegrid-slot {
            @apply h-12;
        }
        .fc .fc-timegrid-slot-label-frame {
            @apply text-xs text-gray-500 dark:text-gray-400;
        }
    </style>
    @endpush
</x-layouts.app>
