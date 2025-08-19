<?php

namespace Database\Seeders;

use App\Models\CourseRegistration;
use App\Models\User;
use App\Models\Course;
use App\Models\Programme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid issues with seeding order
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing data
        DB::table('course_registrations')->truncate();
        
        // Get some students (users with role 'student')
        $students = User::where('role', 'student')->take(5)->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Please run the TestUserSeeder first.');
            return;
        }

        // Get some courses - ensure we have at least 3 courses
        $courses = Course::take(3)->get();
        
        if ($courses->isEmpty()) {
            // If no courses exist, create some sample ones
            $programme = Programme::first();
            
            if (!$programme) {
                $this->command->warn('No programmes found. Please ensure the SchoolProgrammeCourseSeeder has been run.');
                return;
            }
            
            $courses = collect([
                ['name' => 'Introduction to Computer Science', 'programme_id' => $programme->programme_id],
                ['name' => 'Web Development Fundamentals', 'programme_id' => $programme->programme_id],
                ['name' => 'Database Management Systems', 'programme_id' => $programme->programme_id],
            ]);
            
            foreach ($courses as $courseData) {
                $courses[] = Course::create($courseData);
            }
        }

        // Get a programme coordinator to approve registrations
        $coordinator = User::where('role', 'programme_coordinator')->first();
        
        if (!$coordinator) {
            $this->command->warn('No programme coordinator found. Please ensure the TestUserSeeder has been run.');
            return;
        }

        // Create sample registrations
        $statuses = ['pending', 'approved', 'rejected'];
        $reasons = [
            'Incomplete prerequisites',
            'Schedule conflict',
            'Maximum course load reached',
            'Instructor approval required',
            'Department approval required'
        ];
        
        foreach ($students as $student) {
            foreach ($courses as $course) {
                // Randomly decide if the registration is approved, pending, or rejected
                $status = $statuses[array_rand($statuses)];
                
                $registration = [
                    'student_id' => $student->id,
                    'course_id' => $course->course_id ?? $course['programme_id'],
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? $reasons[array_rand($reasons)] : null,
                    'approved_by' => $status === 'approved' ? $coordinator->id : null,
                    'approved_at' => $status === 'approved' ? now() : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ];
                
                try {
                    CourseRegistration::create($registration);
                } catch (\Exception $e) {
                    $this->command->error('Failed to create registration: ' . $e->getMessage());
                    continue;
                }
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Sample course registrations created successfully!');
    }
}
