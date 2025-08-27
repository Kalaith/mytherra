<?php

namespace App\InitData;

use Ramsey\Uuid\Uuid;

class GameInitialConfigData
{
    public static function getData(): array
    {
        return self::getAllConfigs();
    }

    private static function getAllConfigs(): array
    {
        $configs = [];
          $configs = array_merge($configs, self::getHeroConfigs());
        $configs = array_merge($configs, self::getRegionConfigs());
        $configs = array_merge($configs, self::getSettlementConfigs());
        $configs = array_merge($configs, self::getBuildingConfigs());
        $configs = array_merge($configs, self::getLandmarkConfigs());
        $configs = array_merge($configs, self::getDivineInfluenceConfigs());
        $configs = array_merge($configs, self::getSystemConfigs());
        
        return $configs;
    }

    private static function getHeroConfigs(): array
    {
        $configs = [];
          // Basic hero settings
        $configs[] = self::createConfig('hero', 'basic', 'default_age', 20, 'number', 'Default age for newly created heroes');
        $configs[] = self::createConfig('hero', 'basic', 'base_level', 1, 'number', 'Starting level for new heroes');
        $configs[] = self::createConfig('hero', 'basic', 'max_level', 100, 'number', 'Maximum level a hero can reach');
        $configs[] = self::createConfig('hero', 'basic', 'default_alignment_good', 50, 'number', 'Default good alignment value for heroes');
        $configs[] = self::createConfig('hero', 'basic', 'default_alignment_chaotic', 50, 'number', 'Default chaotic alignment value for heroes');
        
        // Leveling settings
        $configs[] = self::createConfig('hero', 'leveling', 'base_level_up_chance', 0.3, 'number', 'Base chance for a hero to level up');
        $configs[] = self::createConfig('hero', 'leveling', 'level_up_difficulty_factor', 0.95, 'number', 'Factor that makes leveling harder as level increases');
        $configs[] = self::createConfig('hero', 'leveling', 'low_level_threshold', 15, 'number', 'Level threshold for accelerated leveling');
        $configs[] = self::createConfig('hero', 'leveling', 'high_level_threshold', 50, 'number', 'Level threshold for slower leveling');
        $configs[] = self::createConfig('hero', 'leveling', 'max_levels_per_year', 5, 'number', 'Maximum levels a hero can gain in one year');

        // Movement settings
        $configs[] = self::createConfig('hero', 'movement', 'chance_to_move', 0.1, 'number', 'Base chance for a hero to move regions');

        // Role settings
        $roles = [
            'scholar' => [
                'name' => 'Scholar',
                'description' => 'A seeker of knowledge and wisdom',
                'base_stats' => [
                    'intelligence' => 15,
                    'strength' => 8,
                    'wisdom' => 12,
                    'charisma' => 10
                ]
            ],
            'warrior' => [
                'name' => 'Warrior',
                'description' => 'A master of combat and strategy',
                'base_stats' => [
                    'intelligence' => 10,
                    'strength' => 15,
                    'wisdom' => 8,
                    'charisma' => 12
                ]
            ]
        ];

        $configs[] = self::createConfig('hero', 'roles', 'role_definitions', $roles, 'array', 'Configuration for hero roles and their base stats');

        return $configs;
    }

    private static function getRegionConfigs(): array
    {
        $configs = [];

        // Basic region settings
        $configs[] = self::createConfig('region', 'basic', 'default_prosperity', 50, 'number', 'Default prosperity for new regions');
        $configs[] = self::createConfig('region', 'basic', 'default_chaos', 50, 'number', 'Default chaos level for new regions');
        $configs[] = self::createConfig('region', 'basic', 'default_magic_affinity', 50, 'number', 'Default magic affinity for new regions');

        // Status thresholds
        $configs[] = self::createConfig('region', 'status', 'prosperity_warning_threshold', 30, 'number', 'Prosperity level that triggers region decline warning');
        $configs[] = self::createConfig('region', 'status', 'prosperity_abandoned_threshold', 15, 'number', 'Prosperity level that triggers region abandonment');
        $configs[] = self::createConfig('region', 'status', 'chaos_threshold', 70, 'number', 'Chaos level that triggers region instability');

        return $configs;
    }

    private static function getSettlementConfigs(): array
    {
        $configs = [];

        // Basic settlement settings
        $configs[] = self::createConfig('settlement', 'basic', 'default_prosperity', 50, 'number', 'Default prosperity for new settlements');
        $configs[] = self::createConfig('settlement', 'basic', 'default_defensibility', 25, 'number', 'Default defensibility for new settlements');

        // Type configurations
        $typeConfigs = [
            'hamlet' => ['min_pop' => 50, 'max_pop' => 300, 'min_buildings' => 5, 'max_buildings' => 15],
            'village' => ['min_pop' => 301, 'max_pop' => 1000, 'min_buildings' => 16, 'max_buildings' => 30],
            'town' => ['min_pop' => 1001, 'max_pop' => 5000, 'min_buildings' => 31, 'max_buildings' => 60],
            'city' => ['min_pop' => 5001, 'max_pop' => 20000, 'min_buildings' => 61, 'max_buildings' => 150]
        ];

        $configs[] = self::createConfig('settlement', 'types', 'type_configurations', $typeConfigs, 'array', 'Configuration for settlement types');

        return $configs;
    }

    private static function getBuildingConfigs(): array
    {
        $configs = [];

        // Basic building settings
        $configs[] = self::createConfig('building', 'basic', 'default_condition', 100, 'number', 'Default condition for new buildings');
        $configs[] = self::createConfig('building', 'basic', 'decay_rate', 1, 'number', 'Rate at which buildings decay per year');

        // Condition level thresholds
        $conditionLevels = [
            'pristine' => ['min' => 90, 'max' => 100],
            'good' => ['min' => 70, 'max' => 89],
            'fair' => ['min' => 50, 'max' => 69],
            'poor' => ['min' => 30, 'max' => 49],
            'ruined' => ['min' => 0, 'max' => 29]
        ];        $configs[] = self::createConfig('building', 'condition', 'condition_levels', $conditionLevels, 'array', 'Building condition level thresholds');

        return $configs;
    }

    private static function getLandmarkConfigs(): array
    {
        $configs = [];

        // Basic landmark settings
        $configs[] = self::createConfig('landmark', 'basic', 'default_magic', 50, 'number', 'Default magic level for landmarks');
        $configs[] = self::createConfig('landmark', 'basic', 'default_danger', 50, 'number', 'Default danger level for landmarks');

        return $configs;
    }

    private static function getDivineInfluenceConfigs(): array
    {
        $configs = [];

        // Divine influence action costs
        $configs[] = self::createConfig('influence', 'costs', 'bless_region', 100, 'number', 'Divine favor cost to bless a region');
        $configs[] = self::createConfig('influence', 'costs', 'curse_region', 150, 'number', 'Divine favor cost to curse a region');
        $configs[] = self::createConfig('influence', 'costs', 'guide_region', 75, 'number', 'Divine favor cost to guide a region');
        $configs[] = self::createConfig('influence', 'costs', 'empower_hero', 100, 'number', 'Divine favor cost to empower a hero');
        $configs[] = self::createConfig('influence', 'costs', 'guide_hero', 75, 'number', 'Divine favor cost to guide a hero');
        $configs[] = self::createConfig('influence', 'costs', 'bless_settlement', 125, 'number', 'Divine favor cost to bless a settlement');

        // Divine effect strengths
        $configs[] = self::createConfig('influence', 'strength', 'weak', 0.5, 'number', 'Multiplier for weak divine effects');
        $configs[] = self::createConfig('influence', 'strength', 'normal', 1.0, 'number', 'Multiplier for normal divine effects');
        $configs[] = self::createConfig('influence', 'strength', 'strong', 1.5, 'number', 'Multiplier for strong divine effects');
        $configs[] = self::createConfig('influence', 'strength', 'overwhelming', 2.0, 'number', 'Multiplier for overwhelming divine effects');

        // Divine effect durations
        $configs[] = self::createConfig('influence', 'duration', 'short', 1, 'number', 'Duration in years for short divine effects');
        $configs[] = self::createConfig('influence', 'duration', 'medium', 3, 'number', 'Duration in years for medium divine effects');
        $configs[] = self::createConfig('influence', 'duration', 'long', 5, 'number', 'Duration in years for long divine effects');
        $configs[] = self::createConfig('influence', 'duration', 'permanent', -1, 'number', 'Duration for permanent divine effects (-1 = permanent)');

        // Divine resonance settings
        $configs[] = self::createConfig('influence', 'resonance', 'default', 50, 'number', 'Default divine resonance for new regions');
        $configs[] = self::createConfig('influence', 'resonance', 'max', 100, 'number', 'Maximum divine resonance value');
        $configs[] = self::createConfig('influence', 'resonance', 'min', 0, 'number', 'Minimum divine resonance value');

        // Basic influence settings
        $configs[] = self::createConfig('influence', 'basic', 'base_cost', 100, 'number', 'Base influence point cost');
        $configs[] = self::createConfig('influence', 'basic', 'cooldown_period', 24, 'number', 'Hours between influence actions');

        return $configs;
    }

    private static function getSystemConfigs(): array
    {
        $configs = [];

        // Game loop settings
        $configs[] = self::createConfig('system', 'gameloop', 'tick_rate', 1, 'number', 'Number of game ticks per real hour');
        $configs[] = self::createConfig('system', 'gameloop', 'tick_duration', 60, 'number', 'Duration of each game tick in minutes');

        return $configs;
    }

    private static function createConfig($category, $subcategory, $key, $value, $dataType, $description): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'category' => $category,
            'subcategory' => $subcategory,
            'key' => $key,
            'value' => is_array($value) ? json_encode($value) : (string)$value,
            'data_type' => $dataType,
            'description' => $description,
            'is_active' => true
        ];
    }
}
