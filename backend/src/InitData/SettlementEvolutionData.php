<?php

namespace App\InitData;

class SettlementEvolutionData
{
    public static function getData(): array
    {
        return [
            'thresholds' => [
                [
                    'settlement_type' => 'hamlet',
                    'next_type' => 'village',
                    'min_population' => 100,
                    'min_prosperity' => 40,
                    'min_influence' => 20,
                    'required_buildings' => json_encode(['market', 'housing']),
                    'evolution_time_days' => 7,
                    'divine_favor_cost' => 100
                ],
                [
                    'settlement_type' => 'village',
                    'next_type' => 'town',
                    'min_population' => 500,
                    'min_prosperity' => 50,
                    'min_influence' => 40,
                    'required_buildings' => json_encode(['market', 'housing', 'tavern', 'craftshop']),
                    'evolution_time_days' => 14,
                    'divine_favor_cost' => 300
                ],
                [
                    'settlement_type' => 'town',
                    'next_type' => 'city',
                    'min_population' => 2000,
                    'min_prosperity' => 60,
                    'min_influence' => 60,
                    'required_buildings' => json_encode(['market', 'housing', 'tavern', 'craftshop', 'temple', 'guild_hall']),
                    'evolution_time_days' => 30,
                    'divine_favor_cost' => 1000
                ]
            ],
            'specializations' => [
                [
                    'code' => 'trade',
                    'name' => 'Trade',
                    'description' => 'Focus on commerce and trade',
                    'base_prosperity_bonus' => 0.3,
                    'base_population_bonus' => 0.2,
                    'weight' => 10,
                    'requirements' => json_encode(['min_prosperity' => 40])
                ],
                [
                    'code' => 'magic',
                    'name' => 'Magic',
                    'description' => 'Focus on magical arts and research',
                    'base_prosperity_bonus' => 0.4,
                    'base_population_bonus' => 0.0,
                    'weight' => 8,
                    'requirements' => json_encode(['region_traits' => ['magical']])
                ],
                [
                    'code' => 'military',
                    'name' => 'Military',
                    'description' => 'Focus on defense and martial training',
                    'base_prosperity_bonus' => 0.1,
                    'base_population_bonus' => 0.1,
                    'weight' => 10,
                    'requirements' => json_encode(['min_defensibility' => 40])
                ]
            ],
            'traits' => [
                [
                    'code' => 'fortified',
                    'name' => 'Fortified',
                    'description' => 'Strong defensive structures',
                    'base_defensibility_bonus' => 0.3,
                    'base_prosperity_bonus' => 0.1,
                    'weight' => 10,
                    'biome_restrictions' => json_encode(['mountainous', 'hills'])
                ],
                [
                    'code' => 'river_crossing',
                    'name' => 'River Crossing',
                    'description' => 'Located at a strategic river crossing',
                    'base_defensibility_bonus' => 0.2,
                    'base_prosperity_bonus' => 0.2,
                    'weight' => 8,
                    'biome_restrictions' => json_encode(['river', 'coastal'])
                ]
            ],
            'evolution_types' => [
                [
                    'code' => 'natural_growth',
                    'name' => 'Natural Growth',
                    'description' => 'Organic population and prosperity growth',
                    'base_weight' => 20,
                    'prosperity_threshold' => 60,
                    'prosperity_modifier' => 1.1,
                    'population_modifier' => 1.15,
                    'defensibility_modifier' => 1.0,
                    'regional_requirements' => null,
                    'settlement_requirements' => json_encode(['min_prosperity' => 40])
                ],
                [
                    'code' => 'decline',
                    'name' => 'Decline',
                    'description' => 'Population and prosperity decline',
                    'base_weight' => 15,
                    'prosperity_threshold' => 40,
                    'prosperity_modifier' => 0.85,
                    'population_modifier' => 0.9,
                    'defensibility_modifier' => 0.95,
                    'regional_requirements' => null,
                    'settlement_requirements' => json_encode(['max_prosperity' => 40])
                ]
            ],
            'parameters' => [
                [
                    'parameter' => 'base_growth_rate',
                    'value' => 0.05,
                    'description' => '5% base population growth per year'
                ],
                [
                    'parameter' => 'max_growth_rate',
                    'value' => 0.15,
                    'description' => '15% maximum population growth'
                ],
                [
                    'parameter' => 'prosperity_growth_modifier',
                    'value' => 0.001,
                    'description' => 'Each point of prosperity adds 0.1% to growth'
                ],
                [
                    'parameter' => 'min_evolution_years',
                    'value' => 5,
                    'description' => 'Minimum years before evolution possible'
                ],
                [
                    'parameter' => 'prosperity_threshold',
                    'value' => 70,
                    'description' => 'Minimum prosperity needed for evolution'
                ]
            ]
        ];
    }
}
