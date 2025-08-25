<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestLecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get the lecturer role
        $lecturerRole = Role::firstOrCreate(
            ['role_name' => 'lecturer'],
            [
                'role_description' => 'Lecturer role with access to teaching related features',
            ]
        );

        // Create test lecturer user
        $lecturer = User::firstOrCreate(
            ['email' => 'lecturer@lsuc.edu.lr'],
            [
                'name' => 'Test Lecturer',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'lecturer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Assign lecturer role if not already assigned
        if ($lecturer->role !== 'lecturer') {
            $lecturer->role = 'lecturer';
            $lecturer->save();
        }

        $this->command->info('Test lecturer created:');
        $this->command->info('Email: lecturer@lsuc.edu.lr');
        $this->command->info('Password: password');
    }
}
