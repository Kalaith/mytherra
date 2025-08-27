<?php

namespace App\InitData;

class RegionStatusData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'status_flourishing',
                'name' => 'Flourishing',
                'code' => 'flourishing',
                'description' => 'A region experiencing exceptional prosperity and growth',
                'hero_spawn_modifier' => 1.5,
                'prosperity_modifier' => 1.4,
                'chaos_modifier' => 0.6,
                'is_active' => true
            ],
            [
                'id' => 'status_prosperous',
                'name' => 'Prosperous',
                'code' => 'prosperous',
                'description' => 'A thriving region with strong economy and stability',
                'hero_spawn_modifier' => 1.2,
                'prosperity_modifier' => 1.2,
                'chaos_modifier' => 0.8,
                'is_active' => true
            ],
            [
                'id' => 'status_stable',
                'name' => 'Stable',
                'code' => 'stable',
                'description' => 'A well-balanced region with steady development',
                'hero_spawn_modifier' => 1.0,
                'prosperity_modifier' => 1.0,
                'chaos_modifier' => 1.0,
                'is_active' => true
            ],
            [
                'id' => 'status_turbulent',
                'name' => 'Turbulent',
                'code' => 'turbulent',
                'description' => 'A region facing social or political unrest',
                'hero_spawn_modifier' => 1.3,
                'prosperity_modifier' => 0.8,
                'chaos_modifier' => 1.4,
                'is_active' => true
            ],
            [
                'id' => 'status_declining',
                'name' => 'Declining',
                'code' => 'declining',
                'description' => 'A region experiencing economic or social deterioration',
                'hero_spawn_modifier' => 0.9,
                'prosperity_modifier' => 0.7,
                'chaos_modifier' => 1.3,
                'is_active' => true
            ],
            [
                'id' => 'status_war_torn',
                'name' => 'War-Torn',
                'code' => 'war_torn',
                'description' => 'A region devastated by conflict and violence',
                'hero_spawn_modifier' => 1.8,
                'prosperity_modifier' => 0.4,
                'chaos_modifier' => 2.0,
                'is_active' => true
            ],
            [
                'id' => 'status_abandoned',
                'name' => 'Abandoned',
                'code' => 'abandoned',
                'description' => 'A forsaken region with minimal civilization',
                'hero_spawn_modifier' => 0.3,
                'prosperity_modifier' => 0.2,
                'chaos_modifier' => 0.5,
                'is_active' => true
            ],
            [
                'id' => 'status_mysterious',
                'name' => 'Mysterious',
                'code' => 'mysterious',
                'description' => 'A region shrouded in unknown forces and strange phenomena',
                'hero_spawn_modifier' => 1.1,
                'prosperity_modifier' => 0.9,
                'chaos_modifier' => 1.6,
                'is_active' => true
            ],
            [
                'id' => 'status_blessed',
                'name' => 'Blessed',
                'code' => 'blessed',
                'description' => 'A region favored by divine intervention and good fortune',
                'hero_spawn_modifier' => 1.4,
                'prosperity_modifier' => 1.3,
                'chaos_modifier' => 0.7,
                'is_active' => true
            ],
            [
                'id' => 'status_cursed',
                'name' => 'Cursed',
                'code' => 'cursed',
                'description' => 'A region afflicted by dark forces and misfortune',
                'hero_spawn_modifier' => 1.6,
                'prosperity_modifier' => 0.6,
                'chaos_modifier' => 1.8,
                'is_active' => true
            ]
        ];
    }
}
