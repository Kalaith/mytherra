<?php

namespace App\InitData;

class HeroData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'hero-001',
                'name' => 'Eldara the Wise',
                'region_id' => 'region-001',
                'role' => 'scholar',
                'description' => 'A wise scholar who seeks knowledge in the arcane arts.',
                'feats' => ['Discovered ancient runes', 'Founded the Academy of Magic'],
                'level' => 3,
                'is_alive' => true,
                'age' => 45,
                'personality_traits' => ['curious', 'patient', 'analytical'],
                'alignment' => ['good' => 70, 'chaotic' => 30],
                'status' => 'living'
            ],
            [
                'id' => 'hero-002',
                'name' => 'Marcus Goldhand',
                'region_id' => 'region-002',
                'role' => 'agent of change',
                'description' => 'A merchant who revolutionized trade in the region.',
                'feats' => ['Established the Grand Bazaar', 'Created the Merchant\'s Guild'],
                'level' => 2,
                'is_alive' => true,
                'age' => 38,
                'personality_traits' => ['ambitious', 'charismatic', 'practical'],
                'alignment' => ['good' => 60, 'chaotic' => 50],
                'status' => 'living'
            ],
            [
                'id' => 'hero-003',
                'name' => 'Sylvana Moonshadow',
                'region_id' => 'region-003',
                'role' => 'prophet',
                'description' => 'A mystic who communicates with the ancient spirits of the vale.',
                'feats' => ['Communed with Ancient Spirits', 'Established the Moonrite Circle'],
                'level' => 4,
                'is_alive' => true,
                'age' => 52,
                'personality_traits' => ['mystical', 'wise', 'mysterious'],
                'alignment' => ['good' => 80, 'chaotic' => 40],
                'status' => 'living'
            ]
        ];
    }
}
