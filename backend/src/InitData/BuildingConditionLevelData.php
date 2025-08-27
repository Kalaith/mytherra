<?php

namespace App\InitData;

class BuildingConditionLevelData
{
    public static function getData(): array
    {
        return [
            [
                'name' => 'Excellent',
                'code' => 'excellent',
                'description' => 'Perfect condition - building operates at peak efficiency',
                'min_condition' => 80,
                'max_condition' => 100,
                'color_code' => '#22c55e', // Green
                'maintenance_multiplier' => 0.8,
                'productivity_multiplier' => 1.2
            ],
            [
                'name' => 'Good',
                'code' => 'good',
                'description' => 'Well-maintained building with minor wear',
                'min_condition' => 60,
                'max_condition' => 79,
                'color_code' => '#84cc16', // Light green
                'maintenance_multiplier' => 0.9,
                'productivity_multiplier' => 1.1
            ],
            [
                'name' => 'Fair',
                'code' => 'fair',
                'description' => 'Average condition with visible wear and minor issues',
                'min_condition' => 40,
                'max_condition' => 59,
                'color_code' => '#eab308', // Yellow
                'maintenance_multiplier' => 1.0,
                'productivity_multiplier' => 1.0
            ],
            [
                'name' => 'Poor',
                'code' => 'poor',
                'description' => 'Deteriorated building requiring significant repairs',
                'min_condition' => 20,
                'max_condition' => 39,
                'color_code' => '#f97316', // Orange
                'maintenance_multiplier' => 1.3,
                'productivity_multiplier' => 0.8
            ],
            [
                'name' => 'Ruined',
                'code' => 'ruined',
                'description' => 'Severely damaged or abandoned building barely functional',
                'min_condition' => 0,
                'max_condition' => 19,
                'color_code' => '#dc2626', // Red
                'maintenance_multiplier' => 2.0,
                'productivity_multiplier' => 0.5
            ]
        ];
    }
}
