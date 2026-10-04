<?php

// Vercel Serverless Function entry point for Laravel
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
    '/tmp/bootstrap/cache',
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

    if (!getenv('APP_CONFIG_CACHE')) {
        putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
    }

    if (!getenv('APP_ROUTES_CACHE')) {
        putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes-v7.php');
    }

    if (!getenv('APP_EVENTS_CACHE')) {
        putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
    }

    if (!getenv('APP_PACKAGES_CACHE')) {
        putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
    }

    if (!getenv('APP_SERVICES_CACHE')) {
        putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
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

// Delegate request processing to standard Laravel entrypoint
require __DIR__ . '/../public/index.php';
