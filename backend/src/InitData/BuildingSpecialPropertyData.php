<?php

namespace App\InitData;

class BuildingSpecialPropertyData
{
    public static function getData(): array
    {
        return [
            [
                'name' => 'Magical',
                'code' => 'magical',
                'description' => 'Imbued with arcane energy that enhances its capabilities',
                'effects' => ['magic_production' => 0.2, 'mana_cost_reduction' => 0.1],
                'rarity' => 'uncommon',
                'category' => 'magical'
            ],
            [
                'name' => 'Ancient',
                'code' => 'ancient',
                'description' => 'Built in ages past with forgotten techniques and materials',
                'effects' => ['durability_bonus' => 0.3, 'cultural_value' => 0.5],
                'rarity' => 'rare',
                'category' => 'historical'
            ],
            [
                'name' => 'Fortified',
                'code' => 'fortified',
                'description' => 'Reinforced to withstand attacks and siege',
                'effects' => ['defense_bonus' => 0.4, 'durability_bonus' => 0.2],
                'rarity' => 'common',
                'category' => 'military'
            ],
            [
                'name' => 'Sacred',
                'code' => 'sacred',
                'description' => 'Blessed by divine powers and used for religious purposes',
                'effects' => ['divine_favor' => 0.3, 'corruption_resistance' => 0.2],
                'rarity' => 'uncommon',
                'category' => 'religious'
            ]
        ];
    }
}
