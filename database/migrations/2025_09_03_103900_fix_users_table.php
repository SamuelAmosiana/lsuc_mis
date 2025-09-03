<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Backup existing users (if any)
        if (Schema::hasTable('users_backup')) {
            Schema::drop('users_backup');
        }
        
        // Create backup of existing users
        DB::statement('CREATE TABLE users_backup AS SELECT * FROM users');
        
        // Truncate users table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // List of all users with their roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@lscollege.test',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Human Resource',
                'email' => 'hr@lsuc.edu',
                'role' => 'hr',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HR Staff',
                'email' => 'hrstaff@lsuc.edu',
                'role' => 'hr',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lecturer',
                'email' => 'lecturer@lsuc.edu.lr',
                'role' => 'lecturer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student',
                'email' => 'student@lscollege.test',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Programmes Coordinator',
                'email' => 'coordinator@lscollege.test',
                'role' => 'coordinator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enrollments',
                'email' => 'enrollment@lscollege.test',
                'role' => 'enrollment',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accounts',
                'email' => 'accounts@lscollege.test',
                'role' => 'accountant',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Front Desk',
                'email' => 'frontdesk@lscollege.test',
                'role' => 'frontdesk',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Librarian',
                'email' => 'librarian@lscollege.test',
                'role' => 'librarian',
                'password' => Hash::make('Libpassword'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Insert all users
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }

    public function down()
    {
        // Restore from backup if needed
        if (Schema::hasTable('users_backup')) {
            DB::statement('TRUNCATE TABLE users');
            DB::statement('INSERT INTO users SELECT * FROM users_backup');
            Schema::drop('users_backup');
        }
    }
};
