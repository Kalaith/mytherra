<?php

namespace App\InitData;

class BuildingData
{
    public static function getData(): array
    {
        return [            [
                'id' => 'building-001',
                'settlement_id' => 'settlement-001', // Crystalhaven
                'type' => 'temple',
                'name' => 'Crystal Temple',
                'condition' => 95,
                'status' => 'active',
                'specialProperties' => ['sacred', 'magical'],
                'level' => 3
            ],
            [
                'id' => 'building-002',
                'settlement_id' => 'settlement-001', // Crystalhaven
                'type' => 'market',
                'name' => 'Crystal Bazaar',
                'condition' => 85,
                'status' => 'active',
                'specialProperties' => ['profitable'],
                'level' => 2
            ],
            [
                'id' => 'building-003',
                'settlement_id' => 'settlement-001', // Crystalhaven
                'type' => 'house',
                'name' => 'Arcane Residence',
                'condition' => 80,
                'status' => 'active',
                'specialProperties' => ['magical'],
                'level' => 1
            ],
            [
                'id' => 'building-004',
                'settlement_id' => 'settlement-001', // Crystalhaven
                'type' => 'manor',
                'name' => 'Mage Council Manor',
                'condition' => 90,
                'status' => 'active',
                'specialProperties' => ['luxurious', 'magical'],
                'level' => 4
            ],
            [
                'id' => 'building-005',
                'settlement_id' => 'settlement-002', // Fellwood Village
                'type' => 'house',
                'name' => 'Woodsman\'s Cottage',
                'condition' => 70,
                'status' => 'active',
                'specialProperties' => [],
                'level' => 1
            ],
            [
                'id' => 'building-006',
                'settlement_id' => 'settlement-002', // Fellwood Village
                'type' => 'house',
                'name' => 'Hunter\'s Lodge',
                'condition' => 75,
                'status' => 'active',
                'specialProperties' => [],
                'level' => 2
            ],
            [
                'id' => 'building-007',
                'settlement_id' => 'settlement-003', // Arcane Observatory
                'type' => 'house',
                'name' => 'Scholar\'s Quarters',
                'condition' => 85,
                'status' => 'active',
                'specialProperties' => ['scholarly'],
                'level' => 2
            ],
            [
                'id' => 'building-008',
                'settlement_id' => 'settlement-003', // Arcane Observatory
                'type' => 'temple',
                'name' => 'Observatory Tower',
                'condition' => 90,
                'status' => 'active',
                'specialProperties' => ['magical', 'ancient'],
                'level' => 5
            ]
        ];
    }
}
