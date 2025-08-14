<?php

// Minimal .env parser
$envPath = __DIR__ . '/../.env';
$vars = [
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'lusaka_south_college_mis',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
];

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }
        $key = substr($line, 0, $pos);
        $value = substr($line, $pos + 1);
        $key = trim($key);
        $value = trim($value);
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }
        if ($key !== '' && array_key_exists($key, $vars)) {
            $vars[$key] = $value;
        }
    }
}

$dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $vars['DB_HOST'], $vars['DB_PORT']);

try {
    $pdo = new PDO($dsn, $vars['DB_USERNAME'], $vars['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

$dbName = preg_replace('/[^a-zA-Z0-9_]/', '', $vars['DB_DATABASE'] ?: 'lusaka_south_college_mis');
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Database ensured: $dbName\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Error ensuring database: ' . $e->getMessage() . "\n");
    exit(1);
}


