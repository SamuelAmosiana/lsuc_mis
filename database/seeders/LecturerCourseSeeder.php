<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LecturerCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test lecturer user
        $lecturer = User::where('email', 'lecturer@lsuc.edu.lr')->first();
        
        if (!$lecturer) {
            $this->command->warn('No lecturer user found. Please run the TestLecturerSeeder first.');
            return;
        }
        
        // Get some courses to assign to the lecturer
        $courses = Course::take(3)->get();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please make sure the courses table has data.');
            return;
        }
        
        // Attach courses to the lecturer
        $lecturer->courses()->sync($courses->pluck('course_id')->toArray());
        
        $this->command->info('Assigned ' . $courses->count() . ' courses to the lecturer.');
    }
}
