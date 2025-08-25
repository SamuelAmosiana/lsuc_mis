<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Test logging
error_log('Test error log message from test-simple-log.php');
file_put_contents(storage_path('logs/test.log'), "Test direct file write\n", FILE_APPEND);

echo "Test log entries created. Check the following files:\n";
echo "- " . ini_get('error_log') . "\n";
echo "- " . storage_path('logs/test.log') . "\n";
