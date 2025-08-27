<?php

namespace App\InitData;

class HeroRoleData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'role-warrior',
                'name' => 'Warrior',
                'code' => 'warrior',
                'description' => 'A skilled fighter who excels in combat and military leadership',
                'primary_attributes' => ['strength', 'constitution', 'leadership'],
                'special_abilities' => ['combat_prowess', 'tactical_knowledge', 'inspire_troops'],
                'starting_level_range' => ['min' => 1, 'max' => 10],
                'is_active' => true
            ],
            [
                'id' => 'role-scholar',
                'name' => 'Scholar',
                'code' => 'scholar',
                'description' => 'A seeker of knowledge and wisdom, scholars excel at research and understanding the world',
                'primary_attributes' => ['intelligence', 'wisdom', 'research'],
                'special_abilities' => ['arcane_knowledge', 'research_boost', 'decipher_mysteries'],
                'starting_level_range' => ['min' => 1, 'max' => 8],
                'is_active' => true
            ],
            [
                'id' => 'role-prophet',
                'name' => 'Prophet',
                'code' => 'prophet',
                'description' => 'A spiritual leader with divine insights, prophets guide communities through spiritual matters',
                'primary_attributes' => ['wisdom', 'charisma', 'intuition'],
                'special_abilities' => ['divine_insight', 'prophecy', 'inspire_faith'],
                'starting_level_range' => ['min' => 1, 'max' => 10],
                'is_active' => true
            ],
            [
                'id' => 'role-agent-of-change',
                'name' => 'Agent of Change',
                'code' => 'agent of change',
                'description' => 'A catalyst for transformation, these heroes drive social or political evolution',
                'primary_attributes' => ['charisma', 'intelligence', 'leadership'],
                'special_abilities' => ['inspire_change', 'social_influence', 'rally_support'],
                'starting_level_range' => ['min' => 1, 'max' => 10],
                'is_active' => true
            ]
        ];
    }
}
