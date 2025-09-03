<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(IllwareConsoleKernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Get the column details for the role column
$columns = DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");

echo "Role column details:\n";
print_r($columns);

// Get distinct roles currently in the database
$roles = DB::select("SELECT DISTINCT role FROM users");
echo "\nCurrent roles in the database:\n";
print_r($roles);

// Get the first user to see the structure
$user = DB::table('users')->first();
echo "\nFirst user in the database:\n";
print_r($user);
