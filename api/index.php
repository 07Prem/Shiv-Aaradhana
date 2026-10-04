<?php

// Vercel Serverless Function entry point for Laravel
define('LARAVEL_START', microtime(true));

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
}

// Delegate request processing to standard Laravel entrypoint
require __DIR__ . '/../public/index.php';
