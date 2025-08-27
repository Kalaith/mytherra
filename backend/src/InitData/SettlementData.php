<?php

namespace App\InitData;

class SettlementData
{
    public static function getData(): array
    {        return [
            [
                'id' => 'settlement-001',
                'name' => 'Crystalhaven',
                'region_id' => 'region-001',
                'type' => 'city',
                'status' => 'prosperous',
                'population' => 3000,
                'prosperity' => 75,
                'defensibility' => 65,
                'specializations' => ['magic_research', 'trade'],
                'founded_year' => 1,
                'events' => [],
                'traits' => ['academic', 'fortified', 'affluent']
            ],
            [
                'id' => 'settlement-002',
                'name' => 'Fellwood Village',
                'region_id' => 'region-001',
                'type' => 'village',
                'status' => 'stable',
                'population' => 500,
                'prosperity' => 45,
                'defensibility' => 30,                'specializations' => ['farming', 'woodworking'],
                'founded_year' => 1,
                'events' => [],
                'traits' => ['rural', 'agricultural']
            ],
            [
                'id' => 'settlement-003',
                'name' => 'Arcane Observatory',
                'region_id' => 'region-001',
                'type' => 'hamlet',
                'status' => 'prosperous',
                'population' => 100,
                'prosperity' => 60,
                'defensibility' => 45,
                'specializations' => ['magic_research', 'astrology'],
                'founded_year' => 1,
                'events' => [],
                'traits' => ['magical', 'research']
            ]
        ];
    }
}
