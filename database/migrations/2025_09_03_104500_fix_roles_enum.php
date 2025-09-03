<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if we need to modify the role column
        $columns = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        
        if (!empty($columns)) {
            $column = $columns[0];
            
            // If the role column is an ENUM, we need to modify it to a string
            if (str_contains(strtolower($column->Type), 'enum')) {
                Schema::table('users', function ($table) {
                    $table->string('role', 50)->change();
                });
            }
        }
        
        // Now insert all users with their roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@lscollege.test',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Human Resource',
                'email' => 'hr@lsuc.edu',
                'role' => 'human_resource',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HR Staff',
                'email' => 'hrstaff@lsuc.edu',
                'role' => 'human_resource',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lecturer',
                'email' => 'lecturer@lsuc.edu.lr',
                'role' => 'lecturer',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student',
                'email' => 'student@lscollege.test',
                'role' => 'student',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Programmes Coordinator',
                'email' => 'coordinator@lscollege.test',
                'role' => 'programme_coordinator',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enrollments',
                'email' => 'enrollment@lscollege.test',
                'role' => 'enrollment_officer',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accounts',
                'email' => 'accounts@lscollege.test',
                'role' => 'accounts',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Front Desk',
                'email' => 'frontdesk@lscollege.test',
                'role' => 'front_desk_officer',
                'password' => 'password',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Librarian',
                'email' => 'librarian@lscollege.test',
                'role' => 'librarian',
                'password' => 'Libpassword',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Truncate the users table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();
        
        // Insert all users
        foreach ($users as $user) {
            $user['password'] = bcrypt($user['password']);
            DB::table('users')->insert($user);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Nothing to do here
    }
};
