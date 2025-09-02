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
        
        // Get the staff profile for the lecturer
        $staffProfile = $lecturer->staffProfile;
        
        if (!$staffProfile) {
            $this->command->warn('No staff profile found for the lecturer. Creating one...');
            $staffProfile = $lecturer->staffProfile()->create([
                'name' => $lecturer->name,
                'email' => $lecturer->email,
                'phone' => '1234567890',
                'address' => '123 Lecturer St',
                'nrc' => '123456/78/9012',
                'gender' => 'Other',
                'next_of_kin' => 'John Doe',
                'department_id' => 1, // Default department
                'position' => 'Lecturer',
                'employment_date' => now(),
            ]);
        }
        
        // Get the staff record
        $staff = $staffProfile->staff;
        
        if (!$staff) {
            $this->command->warn('No staff record found for the lecturer. Creating one...');
            $staff = \App\Models\Staff::create([
                'name' => $lecturer->name,
                'email' => $lecturer->email,
                'phone' => '1234567890',
                'address' => '123 Lecturer St',
                'nrc' => '123456/78/9012',
                'gender' => 'Other',
                'next_of_kin' => 'John Doe',
                'department_id' => 1, // Default department
            ]);
        }
        
        // Attach courses to the staff record
        $staff->courses()->sync($courses->pluck('course_id')->toArray());
        
        $this->command->info('Assigned ' . $courses->count() . ' courses to the lecturer.');
    }
}
