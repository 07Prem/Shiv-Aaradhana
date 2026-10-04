<?php

// Enable error visibility for serverless execution
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ensure required writable storage and cache directories exist in serverless read-only environments
$tmpStorageDirs = [
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

if (is_dir('/tmp')) {
    foreach ($tmpStorageDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    if (!getenv('LARAVEL_STORAGE_PATH')) {
        putenv('LARAVEL_STORAGE_PATH=/tmp/storage');
        $_ENV['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
        $_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
    }

    if (!getenv('VIEW_COMPILED_PATH')) {
        putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
        $_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
        $_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
    }

    // Default encryption key fallback if not configured in Vercel environment variables
    if (!getenv('APP_KEY')) {
        $defaultKey = 'base64:wE6R+3Yn9K2sF8u1m4L7p0Q5v8X1z4A7d2G5j8M1k4=';
        putenv('APP_KEY=' . $defaultKey);
        $_ENV['APP_KEY'] = $defaultKey;
        $_SERVER['APP_KEY'] = $defaultKey;
    }

    // Prepare SQLite database in /tmp if remote MySQL DB_HOST is not configured
    if (!getenv('DB_HOST')) {
        $tmpDb = '/tmp/database.sqlite';
        if (!file_exists($tmpDb)) {
            if (file_exists(__DIR__ . '/../database/database.sqlite')) {
                @copy(__DIR__ . '/../database/database.sqlite', $tmpDb);
            } else {
                @touch($tmpDb);
            }
        }
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=' . $tmpDb);
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = $tmpDb;
        $_SERVER['DB_CONNECTION'] = 'sqlite';
        $_SERVER['DB_DATABASE'] = $tmpDb;
    }
}

// Delegate request processing to standard Laravel entrypoint with full diagnostic capture
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Laravel Serverless Execution Exception</h1>";
    echo "<p><strong>Type:</strong> " . get_class($e) . "</p>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
    echo "<h2>Stack Trace:</h2><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
