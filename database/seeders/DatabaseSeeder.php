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
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            SchoolProgrammeCourseSeeder::class,
            GradeFeeSeeder::class,
            AcademicCalendarSeeder::class,
            TestUserSeeder::class,
            CourseRegistrationSeeder::class,
            PendingCourseRegistrationsSeeder::class, // Add this line
        ]);
    }
}
