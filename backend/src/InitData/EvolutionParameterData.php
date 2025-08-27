<?php

namespace App\InitData;

class EvolutionParameterData
{
    public static function getData(): array
    {
        return [
            [
                'parameter' => 'base_growth_rate',
                'value' => 0.1,
                'description' => 'Base growth rate for settlements'
            ],
            [
                'parameter' => 'max_growth_rate',
                'value' => 0.5,
                'description' => 'Maximum growth rate for settlements'
            ],
            [
                'parameter' => 'prosperity_growth_modifier',
                'value' => 0.2,
                'description' => 'Growth modifier based on prosperity'
            ],
            [
                'parameter' => 'min_evolution_years',
                'value' => 5,
                'description' => 'Minimum years before evolution check'
            ],
            [
                'parameter' => 'prosperity_threshold',
                'value' => 100,
                'description' => 'Prosperity threshold for evolution'
            ]
        ];
    }

    public static function populateData(): void
    {
        echo "Seeding evolution parameters...\n";
        foreach (self::getData() as $paramData) {
            $param = new \App\Models\EvolutionParameter($paramData);
            $param->save();
            echo "Created evolution parameter: {$param->parameter}\n";
        }
        echo "Evolution parameters seeded.\n";
    }
}
