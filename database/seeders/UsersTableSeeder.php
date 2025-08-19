<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HR Manager
        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@lsuc.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'human_resource',
        ]);

        // Create HR Staff
        User::create([
            'name' => 'HR Staff',
            'email' => 'hrstaff@lsuc.edu',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'human_resource',
        ]);

        // Create some test students
        $studentData = [
            ['name' => 'John Doe', 'email' => 'john@student.lsuc.edu'],
            ['name' => 'Jane Smith', 'email' => 'jane@student.lsuc.edu'],
            ['name' => 'Michael Brown', 'email' => 'michael@student.lsuc.edu'],
            ['name' => 'Sarah Johnson', 'email' => 'sarah@student.lsuc.edu'],
            ['name' => 'David Wilson', 'email' => 'david@student.lsuc.edu'],
        ];
        
        foreach ($studentData as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'student',
            ]);
        }
    }
}
