<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have at least one department
        $department = Department::firstOrCreate(
            ['name' => 'Academic Affairs'],
            ['name' => 'Academic Affairs']
        );

        // Super Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@lscollege.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Student
        User::updateOrCreate(
            ['email' => 'student@lscollege.test'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        // Programmes Coordinator
        $coordinator = User::updateOrCreate(
            ['email' => 'coordinator@lscollege.test'],
            [
                'name' => 'Programmes Coordinator',
                'password' => Hash::make('password'),
                'role' => 'programme_coordinator',
                'email_verified_at' => now(),
            ]
        );

        // Create staff record for coordinator if it doesn't exist
        $staff = Staff::firstOrCreate(
            ['email' => 'coordinator@lscollege.test'],
            [
                'name' => 'Programmes Coordinator',
                'phone' => '1234567890',
                'address' => '123 College St',
                'nrc' => '123456/78/9012',
                'gender' => 'Other',
                'next_of_kin' => 'Jane Doe',
                'department_id' => $department->department_id,
            ]
        );

        // Create role if it doesn't exist
        $role = Role::firstOrCreate(
            ['role_name' => 'programme_coordinator'],
            [
                'role_name' => 'programme_coordinator',
                'role_description' => 'Academic planning and coordination'
            ]
        );

        // Assign programme_coordinator role to the staff
        DB::table('staff_role')->updateOrInsert(
            ['staff_id' => $staff->staff_id, 'role_id' => $role->role_id],
            []
        );

        // Accounts User
        $accountsUser = User::updateOrCreate(
            ['email' => 'accounts@lscollege.test'],
            [
                'name' => 'Accounts Officer',
                'password' => Hash::make('password'),
                'role' => 'accounts',
                'email_verified_at' => now(),
            ]
        );

        // Enrollment Officer
        $enrollmentOfficer = User::updateOrCreate(
            ['email' => 'enrollment@lscollege.test'],
            [
                'name' => 'Enrollment Officer',
                'password' => Hash::make('password'),
                'role' => 'enrollment_officer',
                'email_verified_at' => now(),
            ]
        );

        // Front Desk Officer
        $frontDeskOfficer = User::updateOrCreate(
            ['email' => 'frontdesk@lscollege.test'],
            [
                'name' => 'Front Desk Officer',
                'password' => Hash::make('password'),
                'role' => 'front_desk_officer',
                'email_verified_at' => now(),
            ]
        );

        // Librarian
        $librarian = User::updateOrCreate(
            ['email' => 'librarian@lscollege.test'],
            [
                'name' => 'Library Manager',
                'password' => Hash::make('password'),
                'role' => 'librarian',
                'email_verified_at' => now(),
            ]
        );

        // Lecturer
        $lecturer = User::updateOrCreate(
            ['email' => 'lecturer@lscollege.test'],
            [
                'name' => 'Test Lecturer',
                'password' => Hash::make('password'),
                'role' => 'lecturer',
                'email_verified_at' => now(),
            ]
        );

        // Create staff record for lecturer if it doesn't exist
        $lecturerStaff = Staff::firstOrCreate(
            ['email' => 'lecturer@lscollege.test'],
            [
                'name' => 'Test Lecturer',
                'phone' => '1234567890',
                'address' => '456 Faculty St',
                'nrc' => '654321/78/9012',
                'gender' => 'Other',
                'next_of_kin' => 'John Doe',
                'department_id' => $department->department_id,
            ]
        );

        // Create lecturer role if it doesn't exist
        $lecturerRole = Role::firstOrCreate(
            ['role_name' => 'lecturer'],
            [
                'role_name' => 'lecturer',
                'role_description' => 'Teaching and student assessment'
            ]
        );

        // Assign lecturer role to the staff
        DB::table('staff_role')->updateOrInsert(
            ['staff_id' => $lecturerStaff->staff_id, 'role_id' => $lecturerRole->role_id],
            []
        );
    }

}
