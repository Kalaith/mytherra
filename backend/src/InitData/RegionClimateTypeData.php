<?php

namespace App\InitData;

class RegionClimateTypeData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'climate-temperate',
                'name' => 'Temperate',
                'code' => 'temperate',
                'description' => 'Moderate climate with balanced seasons',
                'resource_modifier' => 1.0,
                'population_growth_modifier' => 1.0,
                'is_active' => true
            ],
            [
                'id' => 'climate-arctic',
                'name' => 'Arctic',
                'code' => 'arctic',
                'description' => 'Extremely cold climate with harsh winters',
                'resource_modifier' => 0.7,
                'population_growth_modifier' => 0.6,
                'is_active' => true
            ],
            [
                'id' => 'climate-tropical',
                'name' => 'Tropical',
                'code' => 'tropical',
                'description' => 'Hot and humid climate with abundant rainfall',
                'resource_modifier' => 1.3,
                'population_growth_modifier' => 1.2,
                'is_active' => true
            ],
            [
                'id' => 'climate-arid',
                'name' => 'Arid',
                'code' => 'arid',
                'description' => 'Hot and dry climate with scarce rainfall',
                'resource_modifier' => 0.6,
                'population_growth_modifier' => 0.8,
                'is_active' => true
            ],
            [
                'id' => 'climate-magical',
                'name' => 'Magical',
                'code' => 'magical',
                'description' => 'Climate infused with arcane energy',
                'resource_modifier' => 1.5,
                'population_growth_modifier' => 0.9,
                'is_active' => true
            ]
        ];
    }
}
