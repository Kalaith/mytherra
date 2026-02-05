<?php

// Check for central vendor directory first (Production/XAMPP)
// backend/update_schema.php
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    $loader = require_once __DIR__ . '/../../vendor/autoload.php';
    // Register local App namespace since central autoloader doesn't know about it
    $loader->addPsr4('App\\', __DIR__ . '/src/');
} else {
    // Fallback to local vendor directory (Development)
    require_once __DIR__ . '/vendor/autoload.php';
}

use Dotenv\Dotenv;
use App\External\DatabaseService;
use Illuminate\Database\Capsule\Manager as Schema;
use Illuminate\Database\Schema\Blueprint;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Initialize database service
try {
    DatabaseService::getInstance();
    echo "Database connection established.\n";
} catch (\Exception $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

echo "Checking users table schema...\n";

if (Schema::schema()->hasTable('users')) {
    Schema::schema()->table('users', function (Blueprint $table) {
        $columns = [
            'auth_user_id' => function($t) { $t->unsignedBigInteger('auth_user_id')->nullable()->index()->after('id'); },
            'auth0_id' => function($t) { $t->string('auth0_id')->nullable()->index()->after('auth_user_id'); },
            'auth_email' => function($t) { $t->string('auth_email')->nullable()->after('auth0_id'); },
            'auth_username' => function($t) { $t->string('auth_username')->nullable()->after('auth_email'); },
            'is_active' => function($t) { $t->boolean('is_active')->default(true); },
            'display_name' => function($t) { $t->string('display_name')->nullable(); }
        ];

        foreach ($columns as $colName => $def) {
            if (!Schema::schema()->hasColumn('users', $colName)) {
                echo "Adding column: $colName\n";
                $def($table);
            } else {
                echo "Column exists: $colName\n";
            }
        }
    });
    
    echo "Schema update check completed.\n";
} else {
    echo "Users table does not exist. Creating default table...\n";
    \App\Models\User::createTable();
    echo "Users table created.\n";
}
