<?php

namespace App\InitData;

class LandmarkData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'landmark-001',
                'region_id' => 'region-001',
                'name' => 'Crystal Sanctuary',
                'type' => 'temple',
                'description' => 'An ancient temple made of pure crystal, emanating mystical energy throughout the Arcane Highlands.',
                'status' => 'pristine',
                'magic_level' => 75,
                'danger_level' => 25,
                'discovered_year' => 1,
                'last_visited_year' => 1,
                'associated_events' => [],
                'traits' => ['ancient', 'magical', 'holy_site']
            ],
            [
                'id' => 'landmark-002',
                'region_id' => 'region-001',
                'name' => 'Whispering Grove',
                'type' => 'grove',
                'description' => 'A sacred grove where the trees themselves seem to whisper ancient secrets.',
                'status' => 'pristine',
                'magic_level' => 60,
                'danger_level' => 15,
                'discovered_year' => null,
                'last_visited_year' => null,
                'associated_events' => [],
                'traits' => ['ancient', 'magical', 'hidden']
            ],
            [
                'id' => 'landmark-003',
                'region_id' => 'region-002',
                'name' => 'Merchant\'s Rest Monument',
                'type' => 'monument',
                'description' => 'A grand monument commemorating the founding of the great trade routes.',
                'status' => 'weathered',
                'magic_level' => 20,
                'danger_level' => 10,
                'discovered_year' => 1,
                'last_visited_year' => 1,
                'associated_events' => [],
                'traits' => ['historical', 'strategic']
            ],
            [
                'id' => 'landmark-004',
                'region_id' => 'region-003',
                'name' => 'The Forgotten Tower',
                'type' => 'tower',
                'description' => 'A mysterious tower that appears to be much older than the surrounding landscape.',
                'status' => 'haunted',
                'magic_level' => 85,
                'danger_level' => 70,
                'discovered_year' => null,
                'last_visited_year' => null,
                'associated_events' => [],
                'traits' => ['ancient', 'magical', 'hidden', 'cursed_ground']
            ]
        ];
    }
}
