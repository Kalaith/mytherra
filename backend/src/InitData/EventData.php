<?php

namespace App\InitData;

class EventData
{
    public static function getData(): array
    {        return [
            [
                'id' => 'event-001',
                'title' => 'Academy of Magic Founded',
                'description' => 'The Academy of Magic was founded in the Arcane Highlands, marking a new era of magical learning.',
                'type' => 'founding',
                'status' => 'completed',
                'region_id' => 'region-001',
                'timestamp' => date('Y-m-d H:i:s'),
                'related_region_ids' => ['region-001'],
                'related_hero_ids' => ['hero-001'],
                'year' => 1
            ],
            [
                'id' => 'event-002',
                'title' => 'Grand Bazaar Opens',
                'description' => 'The Grand Bazaar opened in Merchant\'s Haven, attracting traders from across the realm.',
                'type' => 'economic',
                'status' => 'completed',
                'region_id' => 'region-002',
                'timestamp' => date('Y-m-d H:i:s'),
                'related_region_ids' => ['region-002'],
                'related_hero_ids' => ['hero-002'],
                'year' => 1
            ],
            [
                'id' => 'event-003',
                'title' => 'Moonrite Circle Established',
                'description' => 'The Moonrite Circle was established in Mystic Vale, strengthening the region\'s connection to ancient magics.',
                'type' => 'mystical',
                'status' => 'completed',
                'region_id' => 'region-003',
                'timestamp' => date('Y-m-d H:i:s'),
                'related_region_ids' => ['region-003'],
                'related_hero_ids' => ['hero-003'],
                'year' => 1
            ]
        ];
    }
}
