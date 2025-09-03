<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Get the column details for the role column
        $columns = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
        
        echo "Role column details:\n";
        print_r($columns);
        
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
