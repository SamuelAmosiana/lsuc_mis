<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(IllwareConsoleKernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->select('id', 'name', 'email', 'role')->get();

echo "ID\tName\tEmail\tRole\n";
echo str_repeat("-", 80) . "\n";

foreach ($users as $user) {
    echo "{$user->id}\t{$user->name}\t{$user->email}\t{$user->role}\n";
}
