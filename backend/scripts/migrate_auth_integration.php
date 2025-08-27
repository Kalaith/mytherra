<?php

/**
 * Database Migration Script - Add Auth Portal Integration
 * This script adds the users table and necessary indexes for auth portal integration
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\User;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database connection setup
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "Starting Auth Portal Integration Migration...\n";

try {
    // Create users table
    echo "Creating users table...\n";
    User::createTable();
    echo "✓ Users table created successfully\n";

    echo "\nMigration completed successfully!\n";
    echo "The following changes have been made:\n";
    echo "- Added 'users' table with auth portal integration fields\n";
    echo "- Added indexes for efficient querying\n";
    echo "- Ready for auth portal integration\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
