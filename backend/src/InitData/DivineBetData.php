<?php

namespace App\InitData;

class DivineBetData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'bet-001',
                'player_id' => 'SINGLE_PLAYER',
                'bet_type' => 'settlement_growth',
                'target_id' => 'settlement-001',
                'description' => 'Crystalhaven will grow to city status within the next 5 years',
                'timeframe' => 5,
                'confidence' => 'possible',
                'divine_favor_stake' => 100,
                'potential_payout' => 250,
                'current_odds' => 2.5,
                'status' => 'active',
                'placed_year' => 1,
                'resolved_year' => null,
                'resolution_notes' => null
            ],
            [
                'id' => 'bet-002',
                'player_id' => 'SINGLE_PLAYER',
                'bet_type' => 'landmark_discovery',
                'target_id' => 'region-001',
                'description' => 'A new landmark will be discovered in Arcane Highlands within 7 years',
                'timeframe' => 7,
                'confidence' => 'long_shot',
                'divine_favor_stake' => 50,
                'potential_payout' => 200,
                'current_odds' => 4.0,
                'status' => 'active',
                'placed_year' => 1,
                'resolved_year' => null,
                'resolution_notes' => null
            ],
            [
                'id' => 'bet-003',
                'player_id' => 'SINGLE_PLAYER',
                'bet_type' => 'cultural_shift',
                'target_id' => 'region-001',
                'description' => 'Arcane Highlands will shift to mystical cultural influence within 10 years',
                'timeframe' => 10,
                'confidence' => 'possible',
                'divine_favor_stake' => 75,
                'potential_payout' => 225,
                'current_odds' => 3.0,
                'status' => 'active',
                'placed_year' => 1,
                'resolved_year' => null,
                'resolution_notes' => null
            ]
        ];
    }
}
