<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Get the column definition for the role column
        $result = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        
        // Output the result to the console
        echo "Role column definition:\n";
        print_r($result);
        
        // Get distinct roles currently in the database
        $roles = DB::select("SELECT DISTINCT role FROM users");
        echo "\nCurrent roles in the database:\n";
        print_r($roles);
    }

    public function down()
    {
        // Nothing to do here
    }
};
