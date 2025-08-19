<?php

namespace Database\Seeders;

use App\Models\CourseRegistration;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendingCourseRegistrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Get a student user
        $student = User::where('email', 'student@lscollege.test')->first();
        
        if (!$student) {
            $this->command->warn('No student user found. Please run the TestUserSeeder first.');
            return;
        }
        
        // Get some courses
        $courses = Course::take(3)->get();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please run the CourseSeeder first.');
            return;
        }
        
        // Create pending registrations
        $created = 0;
        foreach ($courses as $course) {
            // Skip if this student is already registered for this course
            $exists = CourseRegistration::where('student_id', $student->id)
                ->where('course_id', $course->course_id)
                ->exists();
                
            if (!$exists) {
                CourseRegistration::create([
                    'student_id' => $student->id,
                    'course_id' => $course->course_id,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $created++;
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Created ' . $courses->count() . ' pending course registrations.');
    }
}
