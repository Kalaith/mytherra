<?php

namespace App\InitData;

class BuildingTypeData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'type-house',
                'name' => 'House',
                'code' => 'house',
                'description' => 'Basic residential building for families',
                'category' => 'residential',
                'base_cost' => 50,
                'maintenance_cost' => 5,
                'prosperity_bonus' => 1,
                'defensibility_bonus' => 0,
                'special_properties' => ['housing'],
                'prerequisites' => [],
                'is_active' => true
            ],
            [
                'id' => 'type-manor',
                'name' => 'Manor',
                'code' => 'manor',
                'description' => 'Large residential building for wealthy families',
                'category' => 'residential',
                'base_cost' => 200,
                'maintenance_cost' => 20,
                'prosperity_bonus' => 5,
                'defensibility_bonus' => 2,
                'special_properties' => ['housing', 'luxurious'],
                'prerequisites' => ['house'],
                'is_active' => true
            ]
        ];
    }
}
