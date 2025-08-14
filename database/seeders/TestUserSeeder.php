<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@lscollege.test'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Student
        User::updateOrCreate(
            ['email' => 'student@lscollege.test'],
            [
                'name' => 'Test Student',
                'password' => bcrypt('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );
    }
}


