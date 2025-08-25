<?php

use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illine\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test logging
Log::info('Test log message from test-logging.php');

echo "Log entry created. Check " . storage_path('logs/laravel.log') . "\n";
