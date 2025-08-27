<?php

namespace App\Scripts;

use App\Utils\ConfigurationManager;

/**
 * Manages game configuration initialization
 */
class GameConfigurationManager
{
    /**
     * Initialize all game configurations
     */
    public function initializeConfigurations(): void
    {
        echo "Initializing game configurations...\n";
        
        try {
            ConfigurationManager::initializeConfigurations();
            echo "✅ Game configurations initialized\n";
        } catch (\Exception $e) {
            echo "Error initializing game configurations: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
