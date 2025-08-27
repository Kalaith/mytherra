<?php

namespace App\InitData;

class ResourceNodeStatusData
{
    public static function getData(): array
    {
        return [
            [
                'name' => 'Active',
                'code' => 'active',
                'description' => 'Fully operational and producing resources',
                'output_modifier' => 1.0,
                'extraction_difficulty_modifier' => 0,
                'can_harvest' => true,
                'special_effects' => ['normal_operation']
            ],
            [
                'name' => 'Depleted',
                'code' => 'depleted',
                'description' => 'Resources have been exhausted',
                'output_modifier' => 0.0,
                'extraction_difficulty_modifier' => 0,
                'can_harvest' => false,
                'special_effects' => ['requires_restoration', 'no_output']
            ],
            [
                'name' => 'Contested',
                'code' => 'contested',
                'description' => 'Under dispute or conflict',
                'output_modifier' => 0.3,
                'extraction_difficulty_modifier' => 40,
                'can_harvest' => true,
                'special_effects' => ['conflict_risk', 'reduced_efficiency', 'dangerous_extraction']
            ],
            [
                'name' => 'Corrupted',
                'code' => 'corrupted',
                'description' => 'Tainted by dark forces',
                'output_modifier' => 0.1,
                'extraction_difficulty_modifier' => 60,
                'can_harvest' => false,
                'special_effects' => ['corruption_spread', 'dangerous_exposure', 'requires_cleansing']
            ],
            [
                'name' => 'Flourishing',
                'code' => 'status-flourishing',
                'description' => 'Exceptionally productive and well-maintained',
                'output_modifier' => 1.5,
                'extraction_difficulty_modifier' => -20,
                'can_harvest' => true,
                'special_effects' => ['bonus_yields', 'easier_extraction', 'enhanced_renewal']
            ],
            [
                'name' => 'Overworked',
                'code' => 'overworked',
                'description' => 'Being exploited beyond sustainable levels',
                'output_modifier' => 0.7,
                'extraction_difficulty_modifier' => 25,
                'can_harvest' => true,
                'special_effects' => ['depletion_risk', 'worker_fatigue', 'equipment_strain']
            ],
            [
                'name' => 'Blessed',
                'code' => 'blessed',
                'description' => 'Enhanced by divine or magical blessings',
                'output_modifier' => 1.3,
                'extraction_difficulty_modifier' => -15,
                'can_harvest' => true,
                'special_effects' => ['divine_favor', 'enhanced_yields', 'worker_protection']
            ],
            [
                'name' => 'Unstable',
                'code' => 'unstable',
                'description' => 'Subject to unpredictable changes and fluctuations',
                'output_modifier' => 0.8,
                'extraction_difficulty_modifier' => 30,
                'can_harvest' => true,
                'special_effects' => ['random_fluctuations', 'unpredictable_yields', 'safety_hazards']
            ]
        ];
    }
}
