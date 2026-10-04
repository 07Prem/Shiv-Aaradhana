<?php
header('Content-Type: text/plain');
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Loaded Extensions: " . implode(", ", get_loaded_extensions()) . "\n";
echo "DB_CONNECTION: " . getenv('DB_CONNECTION') . "\n";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "APP_KEY: " . (getenv('APP_KEY') ? 'SET (' . substr(getenv('APP_KEY'), 0, 10) . '...)' : 'EMPTY') . "\n";
echo "/tmp is writable: " . (is_writable('/tmp') ? 'YES' : 'NO') . "\n";
echo "database.sqlite exists in repo: " . (file_exists(__DIR__ . '/../database/database.sqlite') ? 'YES (' . filesize(__DIR__ . '/../database/database.sqlite') . ' bytes)' : 'NO') . "\n";

// Test SQLite connection directly
try {
    $pdo = new PDO('sqlite:/tmp/test.sqlite');
    echo "SQLite Test Connection: SUCCESS\n";
} catch (\Throwable $e) {
    echo "SQLite Test Connection FAILED: " . $e->getMessage() . "\n";
}
