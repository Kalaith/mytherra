<?php

namespace App\InitData;

class HeroSettlementInteractionTypeData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'interaction-visit',
                'name' => 'Visit',
                'code' => 'visit',
                'description' => 'Visit the settlement to gather information',
                'base_duration' => 1,
                'success_chance' => 0.9,
                'influence_cost' => 0,
                'cooldown_hours' => 24,
                'is_active' => true
            ],
            [
                'id' => 'interaction-establish-base',
                'name' => 'Establish Base',
                'code' => 'establish_base',
                'description' => 'Create a permanent presence in the settlement',
                'base_duration' => 5,
                'success_chance' => 0.7,
                'influence_cost' => 10,
                'cooldown_hours' => 168,
                'is_active' => true
            ],
            [
                'id' => 'interaction-quest',
                'name' => 'Quest',
                'code' => 'quest',
                'description' => 'Undertake a quest for the settlement',
                'base_duration' => 3,
                'success_chance' => 0.6,
                'influence_cost' => 5,
                'cooldown_hours' => 72,
                'is_active' => true
            ],
            [
                'id' => 'interaction-trade',
                'name' => 'Trade',
                'code' => 'trade',
                'description' => 'Engage in trade with the settlement',
                'base_duration' => 2,
                'success_chance' => 0.8,
                'influence_cost' => 2,
                'cooldown_hours' => 48,
                'is_active' => true
            ],
            [
                'id' => 'interaction-research',
                'name' => 'Research',
                'code' => 'research',
                'description' => 'Study and research in the settlement',
                'base_duration' => 4,
                'success_chance' => 0.75,
                'influence_cost' => 3,
                'cooldown_hours' => 96,
                'is_active' => true
            ],
            [
                'id' => 'interaction-corruption-cleanse',
                'name' => 'Corruption Cleanse',
                'code' => 'corruption_cleanse',
                'description' => 'Attempt to cleanse corruption from the settlement',
                'base_duration' => 7,
                'success_chance' => 0.4,
                'influence_cost' => 15,
                'cooldown_hours' => 336,
                'is_active' => true
            ],
            [
                'id' => 'interaction-founding',
                'name' => 'Found Settlement',
                'code' => 'founding',
                'description' => 'Found a new settlement',
                'base_duration' => 10,
                'success_chance' => 0.5,
                'influence_cost' => 20,
                'cooldown_hours' => 720,
                'is_active' => true
            ]
        ];
    }
}
