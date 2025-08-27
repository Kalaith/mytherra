<?php

namespace App\InitData;

class RegionData
{
    public static function getData(): array
    {
        return [            [
                'id' => 'region-001',
                'name' => 'Arcane Highlands',
                'color' => '#8B4513',
                'prosperity' => 65,
                'chaos' => 35,
                'magic_affinity' => 80,
                'status' => 'mysterious',
                'event_ids' => [],
                'danger_level' => 30,
                'tags' => ['magical', 'mountainous', 'scholarly'],
                'population_total' => 5000,
                'regional_traits' => ['arcane_nexus', 'high_elevation', 'ancient_ruins'],
                'climate_type' => 'temperate',
                'cultural_influence' => 'scholarly'
            ],
            [
                'id' => 'region-002',
                'name' => 'Merchant\'s Haven',
                'color' => '#DAA520',
                'prosperity' => 85,
                'chaos' => 20,
                'magic_affinity' => 40,
                'status' => 'prosperous',
                'event_ids' => [],
                'danger_level' => 15,
                'tags' => ['commercial', 'coastal', 'wealthy'],
                'population_total' => 12000,
                'regional_traits' => ['trade_hub', 'coastal_bounty', 'cultural_melting_pot'],
                'climate_type' => 'temperate',
                'cultural_influence' => 'mercantile'
            ],
            [
                'id' => 'region-003',
                'name' => 'Mystic Vale',
                'color' => '#228B22',
                'prosperity' => 55,
                'chaos' => 45,
                'magic_affinity' => 90,
                'status' => 'mysterious',
                'event_ids' => [],
                'danger_level' => 50,
                'tags' => ['mystical', 'verdant', 'mysterious'],
                'population_total' => 3000,
                'regional_traits' => ['ley_line_convergence', 'enchanted_forest', 'ancient_mysteries'],
                'climate_type' => 'magical',
                'cultural_influence' => 'mystical'
            ]
        ];
    }
}
