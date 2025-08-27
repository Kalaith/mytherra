<?php

namespace App\InitData;

class SettlementTypeData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'type-hamlet',
                'name' => 'Hamlet',
                'code' => 'hamlet',
                'description' => 'A small rural settlement with a few families',
                'min_population' => 1,
                'max_population' => 100,
                'is_active' => true
            ],
            [
                'id' => 'type-village',
                'name' => 'Village',
                'code' => 'village',
                'description' => 'A small settlement with basic amenities',
                'min_population' => 101,
                'max_population' => 500,
                'is_active' => true
            ],
            [
                'id' => 'type-town',
                'name' => 'Town',
                'code' => 'town',
                'description' => 'A medium-sized settlement with diverse services',
                'min_population' => 501,
                'max_population' => 2000,
                'is_active' => true
            ],
            [
                'id' => 'type-city',
                'name' => 'City',
                'code' => 'city',
                'description' => 'A large settlement with significant infrastructure',
                'min_population' => 2001,
                'max_population' => 10000,
                'is_active' => true
            ],
            [
                'id' => 'type-metropolis',
                'name' => 'Metropolis',
                'code' => 'metropolis',
                'description' => 'A major urban center of great importance',
                'min_population' => 10001,
                'max_population' => null,
                'is_active' => true
            ],
            [
                'id' => 'type-outpost',
                'name' => 'Outpost',
                'code' => 'outpost',
                'description' => 'A small frontier settlement',
                'min_population' => 5,
                'max_population' => 50,
                'is_active' => true
            ],
            [
                'id' => 'type-stronghold',
                'name' => 'Stronghold',
                'code' => 'stronghold',
                'description' => 'A fortified settlement focused on defense',
                'min_population' => 100,
                'max_population' => 1000,
                'is_active' => true
            ]
        ];
    }

    public static function populateData(): void
    {
        // Use the model to insert all seed data
        $modelClass = \App\Models\SettlementType::class;
        foreach (self::getData() as $item) {
            $modelClass::create($item);
        }
    }
}
