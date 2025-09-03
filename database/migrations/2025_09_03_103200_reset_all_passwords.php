<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // List of all user emails that should exist with valid roles
        $users = [
            'admin@lscollege.test' => ['name' => 'Super Admin', 'role' => 'admin'],
            'hr@lsuc.edu' => ['name' => 'Human Resource', 'role' => 'hr'],
            'hrstaff@lsuc.edu' => ['name' => 'HR Staff', 'role' => 'hr'],
            'lecturer@lsuc.edu.lr' => ['name' => 'Lecturer', 'role' => 'lecturer'],
            'student@lscollege.test' => ['name' => 'Student', 'role' => 'student'],
            'coordinator@lscollege.test' => ['name' => 'Programmes Coordinator', 'role' => 'coordinator'],
            'enrollment@lscollege.test' => ['name' => 'Enrollments', 'role' => 'enrollment'],
            'accounts@lscollege.test' => ['name' => 'Accounts', 'role' => 'accountant'],
            'frontdesk@lscollege.test' => ['name' => 'Front Desk', 'role' => 'frontdesk'],
            'librarian@lscollege.test' => ['name' => 'Librarian', 'role' => 'librarian']
        ];

        foreach ($users as $email => $data) {
            $user = DB::table('users')->where('email', $email)->first();
            
            if (!$user) {
                // Create user if doesn't exist
                DB::table('users')->insert([
                    'name' => $data['name'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => $data['role'],
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "Created user: {$email}\n";
            } else {
                // Update existing user password
                DB::table('users')
                    ->where('email', $email)
                    ->update([
                        'password' => Hash::make('password'),
                        'updated_at' => now(),
                    ]);
                echo "Updated password for: {$email}\n";
            }
        }
    }

    public function down()
    {
        // No need to revert this change
    }
};
