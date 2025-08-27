<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\ConfigurationManager;

// Initialize configurations
echo "Initializing game configurations...\n";
try {
    ConfigurationManager::initializeConfigurations();
    echo "Game configurations initialized successfully\n";
} catch (Exception $e) {
    echo "Error initializing configurations: " . $e->getMessage() . "\n";
    exit(1);
}
