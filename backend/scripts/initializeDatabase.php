<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Scripts\EnvironmentManager;
use App\Scripts\DatabaseSchemaManager;
use App\Scripts\GameDataSeeder;
use App\Scripts\GameConfigurationManager;

echo "=== Mytherra Database Initialization ===\n";
echo "WARNING: This will completely clear and rebuild the database!\n";
echo "Starting database initialization...\n\n";

try {
    // Step 1: Load environment variables
    echo "Step 1: Loading environment...\n";
    $environmentManager = new EnvironmentManager();
    $environmentManager->loadEnvironment();

    // Step 2: Initialize database structure
    echo "\nStep 2: Initializing database structure...\n";
    $schemaManager = new DatabaseSchemaManager();
    $schemaManager->initializeDatabase();
    $schemaManager->createTables();

    // Step 3: Seed game data
    echo "\nStep 3: Seeding game data...\n";
    $dataSeeder = new GameDataSeeder();
    $dataSeeder->seedAllData();

    // Step 4: Initialize game configurations
    echo "\nStep 4: Initializing game configurations...\n";
    $configManager = new GameConfigurationManager();
    $configManager->initializeConfigurations();    echo "\n=== Database Initialization Complete ===\n";
    echo "✅ Database structure created\n";
    echo "✅ Game configurations initialized\n";
    echo "✅ Game data seeded\n";
    echo "\nYou can now run the game or test scripts.\n";
    
} catch (Exception $e) {
    echo "\n=== Database Initialization Failed ===\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    echo "Please check your database configuration and try again.\n";
    exit(1);
}
