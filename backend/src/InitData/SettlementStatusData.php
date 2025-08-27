<?php

namespace App\InitData;

class SettlementStatusData
{
    public static function getData(): array
    {        return [
            [
                'id' => 'status_thriving',
                'name' => 'Thriving',
                'code' => 'thriving',
                'description' => 'A settlement experiencing rapid growth and prosperity',
                'prosperity_modifier' => 1.5,
                'growth_modifier' => 1.3,
                'is_active' => true
            ],
            [
                'id' => 'status_prosperous',
                'name' => 'Prosperous',
                'code' => 'prosperous',
                'description' => 'A thriving settlement with strong economy and growth',
                'prosperity_modifier' => 1.2,
                'growth_modifier' => 1.1,
                'is_active' => true
            ],
            [
                'id' => 'status_stable',
                'name' => 'Stable',
                'code' => 'stable',
                'description' => 'A well-maintained settlement with steady development',
                'prosperity_modifier' => 1.0,
                'growth_modifier' => 1.0,
                'is_active' => true
            ],
            [
                'id' => 'status_declining',
                'name' => 'Declining',
                'code' => 'declining',
                'description' => 'A settlement facing economic or social challenges',
                'prosperity_modifier' => 0.8,
                'growth_modifier' => 0.9,
                'is_active' => true
            ],
            [
                'id' => 'status_struggling',
                'name' => 'Struggling',
                'code' => 'struggling',
                'description' => 'A settlement dealing with significant hardships',
                'prosperity_modifier' => 0.6,
                'growth_modifier' => 0.7,
                'is_active' => true
            ],            [
                'id' => 'status_abandoned',
                'name' => 'Abandoned',
                'code' => 'abandoned',
                'description' => 'A deserted settlement with no active population',
                'prosperity_modifier' => 0.0,
                'growth_modifier' => 0.0,
                'is_active' => true
            ],
            [
                'id' => 'status_ruined',
                'name' => 'Ruined',
                'code' => 'ruined',
                'description' => 'A settlement destroyed or fallen into complete disrepair',
                'prosperity_modifier' => 0.0,
                'growth_modifier' => 0.0,
                'is_active' => true
            ]
        ];
    }
}
