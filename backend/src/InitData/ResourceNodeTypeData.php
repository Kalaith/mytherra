<?php

namespace App\InitData;

class ResourceNodeTypeData
{
    public static function getData(): array
    {
        return [
            [
                'name' => 'Mine',
                'code' => 'mine',
                'description' => 'An excavation site for extracting minerals and metals',
                'base_output' => 60,
                'extraction_difficulty' => 70,
                'renewal_rate' => 0,
                'properties' => ['valuable', 'dangerous', 'finite'],
                'resource_category' => 'mineral'
            ],
            [
                'name' => 'Quarry',
                'code' => 'quarry',
                'description' => 'A site for extracting stone and building materials',
                'base_output' => 55,
                'extraction_difficulty' => 60,
                'renewal_rate' => 0,
                'properties' => ['steady', 'reliable', 'finite'],
                'resource_category' => 'stone'
            ],
            [
                'name' => 'Forest',
                'code' => 'forest',
                'description' => 'A woodland area providing timber and natural resources',
                'base_output' => 45,
                'extraction_difficulty' => 40,
                'renewal_rate' => 20,
                'properties' => ['renewable', 'abundant', 'seasonal'],
                'resource_category' => 'timber'
            ],
            [
                'name' => 'Farmland',
                'code' => 'farmland',
                'description' => 'Agricultural land for food production',
                'base_output' => 50,
                'extraction_difficulty' => 30,
                'renewal_rate' => 40,
                'properties' => ['seasonal', 'fertile', 'renewable'],
                'resource_category' => 'food'
            ],
            [
                'name' => 'Fishing Waters',
                'code' => 'fishing',
                'description' => 'A water body providing fish and aquatic resources',
                'base_output' => 40,
                'extraction_difficulty' => 35,
                'renewal_rate' => 30,
                'properties' => ['variable', 'coastal', 'renewable'],
                'resource_category' => 'food'
            ],
            [
                'name' => 'Magical Spring',
                'code' => 'magical_spring',
                'description' => 'A mystical source of magical energy and rare materials',
                'base_output' => 80,
                'extraction_difficulty' => 90,
                'renewal_rate' => 10,
                'properties' => ['magical', 'rare', 'powerful', 'unstable'],
                'resource_category' => 'magical'
            ],
            [
                'name' => 'Herb Garden',
                'code' => 'herb_garden',
                'description' => 'Cultivated area for growing medicinal and magical herbs',
                'base_output' => 35,
                'extraction_difficulty' => 25,
                'renewal_rate' => 50,
                'properties' => ['medicinal', 'magical', 'renewable', 'cultivated'],
                'resource_category' => 'herbs'
            ]
        ];
    }
}
