<?php

namespace App\InitData;

class RegionCulturalInfluenceData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'culture-pastoral',
                'name' => 'Pastoral',
                'code' => 'pastoral',
                'description' => 'Rural culture focused on agriculture and animal husbandry',
                'hero_spawn_rate_modifier' => 0.8,
                'development_modifier' => 0.7,
                'stability_modifier' => 1.2,
                'is_active' => true
            ],
            [
                'id' => 'culture-mercantile',
                'name' => 'Mercantile',
                'code' => 'mercantile',
                'description' => 'Trade-focused culture with emphasis on commerce',
                'hero_spawn_rate_modifier' => 1.1,
                'development_modifier' => 1.3,
                'stability_modifier' => 0.9,
                'is_active' => true
            ],
            [
                'id' => 'culture-martial',
                'name' => 'Martial',
                'code' => 'martial',
                'description' => 'Warrior culture with strong military traditions',
                'hero_spawn_rate_modifier' => 1.4,
                'development_modifier' => 0.8,
                'stability_modifier' => 0.7,
                'is_active' => true
            ],
            [
                'id' => 'culture-mystical',
                'name' => 'Mystical',
                'code' => 'mystical',
                'description' => 'Magic-focused culture with arcane traditions',
                'hero_spawn_rate_modifier' => 1.2,
                'development_modifier' => 1.0,
                'stability_modifier' => 0.8,
                'is_active' => true
            ],
            [
                'id' => 'culture-nomadic',
                'name' => 'Nomadic',
                'code' => 'nomadic',
                'description' => 'Mobile culture with minimal permanent settlements',
                'hero_spawn_rate_modifier' => 1.0,
                'development_modifier' => 0.6,
                'stability_modifier' => 0.5,
                'is_active' => true            ],
            [
                'id' => 'culture-scholarly',
                'name' => 'Scholarly',
                'code' => 'scholarly',
                'description' => 'Academic culture focused on knowledge and learning',
                'hero_spawn_rate_modifier' => 1.1,
                'development_modifier' => 1.3,
                'stability_modifier' => 1.1,
                'is_active' => true
            ]
        ];
    }
}
