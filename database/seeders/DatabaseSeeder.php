<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
        $this->call(RoleSeeder::class);
        
        // Seed test users
        $this->call(TestUserSeeder::class);
        
        // Seed test lecturer
        $this->call(TestLecturerSeeder::class);
        
        // Seed other data
        $this->call([
            DepartmentSeeder::class,
            SchoolProgrammeCourseSeeder::class,
            GradeFeeSeeder::class,
            AcademicCalendarSeeder::class,
            CourseRegistrationSeeder::class,
            PendingCourseRegistrationsSeeder::class,
            // HR Module Seeders
            ProgramsTableSeeder::class,
            UsersTableSeeder::class,
            StudentsTableSeeder::class,
            StatusNotesTableSeeder::class,
        ]);
        
        // Assign courses to lecturer
        $this->call(LecturerCourseSeeder::class);
    }
}
