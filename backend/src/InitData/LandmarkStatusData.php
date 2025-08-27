<?php

namespace App\InitData;

class LandmarkStatusData
{
    public static function getData(): array
    {
        return [
            [
                'name' => 'Pristine',
                'code' => 'pristine',
                'description' => 'Landmark in perfect condition, untouched by time or corruption',
                'magic_modifier' => 20,
                'danger_modifier' => -10,
                'exploration_difficulty_modifier' => -15,
                'special_effects' => ['discovery_bonus' => 0.2],
                'is_active' => true
            ],
            [
                'name' => 'Corrupted',
                'code' => 'corrupted',
                'description' => 'Landmark tainted by dark forces or magical corruption',
                'magic_modifier' => -20,
                'danger_modifier' => 30,
                'exploration_difficulty_modifier' => 20,
                'special_effects' => ['corruption_spread' => 0.1],
                'is_active' => true
            ],
            [
                'name' => 'Awakened',
                'code' => 'awakened',
                'description' => 'Landmark pulsing with newly awakened magical energy',
                'magic_modifier' => 40,
                'danger_modifier' => 15,
                'exploration_difficulty_modifier' => 10,
                'special_effects' => ['magic_resonance' => 0.3],
                'is_active' => true
            ],
            [
                'name' => 'Dormant',
                'code' => 'dormant',
                'description' => 'Landmark in a state of magical slumber',
                'magic_modifier' => -10,
                'danger_modifier' => 0,
                'exploration_difficulty_modifier' => 0,
                'special_effects' => null,
                'is_active' => true
            ],
            [
                'name' => 'Unstable',
                'code' => 'unstable',
                'description' => 'Landmark exhibiting unpredictable magical fluctuations',
                'magic_modifier' => 30,
                'danger_modifier' => 25,
                'exploration_difficulty_modifier' => 30,
                'special_effects' => ['random_events' => 0.2],
                'is_active' => true
            ]
        ];
    }
}
