<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        
        $events = [
            [
                'title' => 'Semester 1 Begins',
                'description' => 'First day of classes for Semester 1',
                'start_date' => $now->copy()->addDays(1)->toDateString(),
                'end_date' => $now->copy()->addDays(1)->toDateString(),
                'type' => 'academic',
                'color' => '#3b82f6',
            ],
            [
                'title' => 'Midterm Examinations',
                'description' => 'Midterm examinations for all courses',
                'start_date' => $now->copy()->addDays(15)->toDateString(),
                'end_date' => $now->copy()->addDays(19)->toDateString(),
                'type' => 'exam',
                'color' => '#ef4444',
            ],
            [
                'title' => 'Lecturer Development Workshop',
                'description' => 'Workshop on innovative teaching methodologies',
                'start_date' => $now->copy()->addDays(5)->toDateString(),
                'end_date' => $now->copy()->addDays(5)->toDateString(),
                'type' => 'workshop',
                'color' => '#8b5cf6',
            ],
            [
                'title' => 'Department Meeting',
                'description' => 'Monthly department staff meeting',
                'start_date' => $now->copy()->addDays(3)->toDateString(),
                'end_date' => $now->copy()->addDays(3)->toDateString(),
                'type' => 'meeting',
                'color' => '#10b981',
            ],
            [
                'title' => 'Final Examinations',
                'description' => 'End of semester examinations',
                'start_date' => $now->copy()->addDays(30)->toDateString(),
                'end_date' => $now->copy()->addDays(37)->toDateString(),
                'type' => 'exam',
                'color' => '#ef4444',
            ],
        ];

        foreach ($events as $event) {
            AcademicCalendar::create($event);
        }
    }
}
