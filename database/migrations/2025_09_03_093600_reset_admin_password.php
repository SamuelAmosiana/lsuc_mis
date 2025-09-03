<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Reset admin password to 'password'
        DB::table('users')
            ->where('email', 'admin@lscollege.test')
            ->update([
                'password' => Hash::make('password'),
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        // No need to revert this change
    }
};
