<?php

namespace App\InitData;

class LandmarkTypeData
{
    public static function getData(): array
    {
        return [
            [
                'name' => 'Temple',
                'code' => 'temple',
                'description' => 'Ancient place of worship and spiritual power',
                'base_magic_level' => 30,
                'base_danger_level' => 20,
                'discovery_difficulty' => 40,
                'exploration_rewards' => [
                    'divine_favor' => 100,
                    'ancient_knowledge' => true
                ],
                'special_properties' => [
                    'spiritual_resonance' => true,
                    'blessing_potential' => 0.3
                ],
                'is_active' => true
            ],
            [
                'name' => 'Ruin',
                'code' => 'ruin',
                'description' => 'Remains of an ancient civilization',
                'base_magic_level' => 20,
                'base_danger_level' => 40,
                'discovery_difficulty' => 50,
                'exploration_rewards' => [
                    'artifacts' => true,
                    'historical_knowledge' => true
                ],
                'special_properties' => [
                    'hidden_treasures' => 0.4,
                    'trap_potential' => 0.3
                ],
                'is_active' => true
            ],
            [
                'name' => 'Sacred Grove',
                'code' => 'grove',
                'description' => 'Natural sanctuary of primal magic',
                'base_magic_level' => 40,
                'base_danger_level' => 15,
                'discovery_difficulty' => 35,
                'exploration_rewards' => [
                    'natural_resources' => true,
                    'magical_essence' => true
                ],
                'special_properties' => [
                    'nature_magic' => 0.5,
                    'healing_potential' => 0.4
                ],
                'is_active' => true
            ],
            [
                'name' => 'Ancient Tower',
                'code' => 'tower',
                'description' => 'Mysterious tower from a forgotten age',
                'base_magic_level' => 50,
                'base_danger_level' => 35,
                'discovery_difficulty' => 45,
                'exploration_rewards' => [
                    'magical_knowledge' => true,
                    'arcane_artifacts' => true
                ],
                'special_properties' => [
                    'arcane_study' => 0.5,
                    'magical_defense' => 0.4
                ],
                'is_active' => true
            ],
            [
                'name' => 'Battlefield',
                'code' => 'battlefield',
                'description' => 'Site of a historic battle',
                'base_magic_level' => 25,
                'base_danger_level' => 45,
                'discovery_difficulty' => 30,
                'exploration_rewards' => [
                    'military_artifacts' => true,
                    'battle_knowledge' => true
                ],
                'special_properties' => [
                    'martial_resonance' => 0.4,
                    'haunted' => 0.3
                ],
                'is_active' => true
            ]
        ];
    }
}
