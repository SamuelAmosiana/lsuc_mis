<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Check if admin exists
        $admin = DB::table('users')->where('email', 'admin@lscollege.test')->first();
        
        if (!$admin) {
            // Create admin if doesn't exist
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@lscollege.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "Created new admin user\n";
        } else {
            // Update existing admin password
            DB::table('users')
                ->where('email', 'admin@lscollege.test')
                ->update([
                    'password' => Hash::make('password'),
                    'updated_at' => now(),
                ]);
            echo "Updated existing admin password\n";
        }
    }

    public function down()
    {
        // No need to revert this change
    }
};
