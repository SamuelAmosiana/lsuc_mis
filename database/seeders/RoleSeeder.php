<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'super_admin', 'role_description' => 'Full system control'],
            ['role_name' => 'admin', 'role_description' => 'Basic administrative tasks'],
            ['role_name' => 'programme_coordinator', 'role_description' => 'Academic planning'],
            ['role_name' => 'human_resource', 'role_description' => 'HR operations'],
            ['role_name' => 'enrollment_officer', 'role_description' => 'Student applications'],
            ['role_name' => 'accounts', 'role_description' => 'Finance operations'],
            ['role_name' => 'front_desk_officer', 'role_description' => 'Front desk operations'],
            ['role_name' => 'librarian', 'role_description' => 'Library operations'],
            ['role_name' => 'lecturer', 'role_description' => 'Teaching staff'],
            ['role_name' => 'student', 'role_description' => 'Learner'],
        ];

        foreach ($roles as $role) {
            DB::table('role')->updateOrInsert(
                ['role_name' => $role['role_name']],
                ['role_description' => $role['role_description']]
            );
        }
    }
}


