<?php

namespace App\InitData;

class BettingConfigData
{
    public static function getBetTypes(): array
    {
        return [
            [
                'code' => 'settlement_growth',
                'description' => 'A bet on whether a settlement will grow in size',
                'base_odds' => 2.5,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'Settlement population increases by at least 20% within timeframe'
            ],
            [
                'code' => 'landmark_discovery',
                'description' => 'A bet on whether a new landmark will be discovered',
                'base_odds' => 4.0,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'New landmark is discovered within the specified region within timeframe'
            ],
            [
                'code' => 'cultural_shift',
                'description' => 'A bet on cultural changes within a settlement or region',
                'base_odds' => 3.0,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'Cultural traits of the target change significantly within timeframe'
            ],
            [
                'code' => 'hero_settlement_bond',
                'description' => 'A bet on whether a hero will form a bond with a settlement',
                'base_odds' => 3.5,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'Hero becomes associated with the specified settlement within timeframe'
            ],
            [
                'code' => 'hero_location_visit',
                'description' => 'A bet on whether a hero will visit a specific location',
                'base_odds' => 2.8,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'Hero visits the target location within timeframe'
            ],
            [
                'code' => 'settlement_transformation',
                'description' => 'A bet on a major transformation of a settlement',
                'base_odds' => 5.0,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'Settlement changes type or undergoes significant transformation within timeframe'
            ],
            [
                'code' => 'corruption_spread',
                'description' => 'A bet on whether corruption will spread to an area',
                'base_odds' => 3.5,
                'min_timeframe' => 1,
                'max_timeframe' => 50,
                'resolve_conditions' => 'Corruption level increases significantly in target area within timeframe'
            ]
        ];
    }

    public static function getConfidenceLevels(): array
    {
        return [
            [
                'code' => 'long_shot',
                'description' => 'Very unlikely to happen',
                'odds_modifier' => 2.0,
                'stake_multiplier' => 0.5
            ],
            [
                'code' => 'possible',
                'description' => 'Could reasonably happen',
                'odds_modifier' => 1.0,
                'stake_multiplier' => 1.0
            ],
            [
                'code' => 'likely',
                'description' => 'More likely to happen than not',
                'odds_modifier' => 0.7,
                'stake_multiplier' => 1.5
            ],
            [
                'code' => 'near_certain',
                'description' => 'Almost guaranteed to happen',
                'odds_modifier' => 0.4,
                'stake_multiplier' => 2.0
            ]
        ];
    }

    public static function getTimeframeModifiers(): array
    {
        return [
            ['max_timeframe' => 1, 'modifier' => 1.5],
            ['max_timeframe' => 3, 'modifier' => 1.2],
            ['max_timeframe' => 5, 'modifier' => 1.0],
            ['max_timeframe' => 10, 'modifier' => 0.8],
            ['max_timeframe' => 50, 'modifier' => 0.6]
        ];
    }

    public static function getSystemConfig(): array
    {
        return [
            [
                'code' => 'min_divine_favor_stake',
                'value' => 10,
                'description' => 'Minimum amount of divine favor that can be staked on a bet'
            ],
            [
                'code' => 'max_divine_favor_stake',
                'value' => 1000,
                'description' => 'Maximum amount of divine favor that can be staked on a bet'
            ]
        ];
    }
}
