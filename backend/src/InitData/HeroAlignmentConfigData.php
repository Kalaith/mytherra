<?php

namespace App\InitData;

class HeroAlignmentConfigData
{
    public static function getData(): array
    {
        return [
            'traits' => [
                // Personality traits
                [
                    'code' => 'altruistic',
                    'name' => 'Altruistic',
                    'description' => 'Places others\' needs before their own',
                    'base_influence' => 0.8,
                    'category' => 'personality',
                    'opposing_trait_code' => 'selfish'
                ],
                [
                    'code' => 'selfish',
                    'name' => 'Selfish',
                    'description' => 'Primarily concerned with personal gain',
                    'base_influence' => 1.2,
                    'category' => 'personality',
                    'opposing_trait_code' => 'altruistic'
                ],
                [
                    'code' => 'cautious',
                    'name' => 'Cautious',
                    'description' => 'Careful and methodical in approach',
                    'base_influence' => 0.7,
                    'category' => 'personality',
                    'opposing_trait_code' => 'reckless'
                ],
                [
                    'code' => 'reckless',
                    'name' => 'Reckless',
                    'description' => 'Takes risks without much consideration',
                    'base_influence' => 1.3,
                    'category' => 'personality',
                    'opposing_trait_code' => 'cautious'
                ],

                // Morality traits
                [
                    'code' => 'honorable',
                    'name' => 'Honorable',
                    'description' => 'Strong sense of moral duty',
                    'base_influence' => 0.9,
                    'category' => 'morality',
                    'opposing_trait_code' => 'dishonorable'
                ],
                [
                    'code' => 'dishonorable',
                    'name' => 'Dishonorable',
                    'description' => 'Disregards moral conventions',
                    'base_influence' => 1.1,
                    'category' => 'morality',
                    'opposing_trait_code' => 'honorable'
                ],

                // Motivation traits
                [
                    'code' => 'ambitious',
                    'name' => 'Ambitious',
                    'description' => 'Driven by personal achievement',
                    'base_influence' => 1.2,
                    'category' => 'motivation',
                    'opposing_trait_code' => 'content'
                ],
                [
                    'code' => 'content',
                    'name' => 'Content',
                    'description' => 'Satisfied with current status',
                    'base_influence' => 0.8,
                    'category' => 'motivation',
                    'opposing_trait_code' => 'ambitious'
                ]
            ],

            'modifiers' => [
                // Settlement interaction modifiers
                [
                    'trigger_type' => 'settlement_interaction',
                    'trigger_condition' => 'help_town',
                    'trait_code' => 'altruistic',
                    'modifier_value' => 0.2,
                    'description' => 'Helping a settlement strengthens altruistic trait'
                ],
                [
                    'trigger_type' => 'settlement_interaction',
                    'trigger_condition' => 'exploit_town',
                    'trait_code' => 'selfish',
                    'modifier_value' => 0.2,
                    'description' => 'Exploiting a settlement strengthens selfish trait'
                ],

                // Combat modifiers
                [
                    'trigger_type' => 'combat',
                    'trigger_condition' => 'flee',
                    'trait_code' => 'cautious',
                    'modifier_value' => 0.15,
                    'description' => 'Fleeing from danger reinforces cautious nature'
                ],
                [
                    'trigger_type' => 'combat',
                    'trigger_condition' => 'engage_stronger',
                    'trait_code' => 'reckless',
                    'modifier_value' => 0.25,
                    'description' => 'Engaging stronger opponents increases reckless trait'
                ],

                // Quest modifiers
                [
                    'trigger_type' => 'quest',
                    'trigger_condition' => 'complete_honorably',
                    'trait_code' => 'honorable',
                    'modifier_value' => 0.2,
                    'description' => 'Completing quests honorably strengthens honor'
                ],
                [
                    'trigger_type' => 'quest',
                    'trigger_condition' => 'complete_dishonorably',
                    'trait_code' => 'dishonorable',
                    'modifier_value' => 0.2,
                    'description' => 'Using dishonorable means increases dishonor'
                ]
            ],

            'event_responses' => [
                [
                    'event_type' => 'settlement_attack',
                    'required_trait_code' => 'honorable',
                    'response_type' => 'defend_settlement',
                    'probability' => 0.8,
                    'influence_modifier' => 1.2,
                    'description' => 'Honorable heroes likely to defend settlements'
                ],
                [
                    'event_type' => 'treasure_found',
                    'required_trait_code' => 'altruistic',
                    'response_type' => 'share_treasure',
                    'probability' => 0.7,
                    'influence_modifier' => 0.8,
                    'description' => 'Altruistic heroes likely to share treasure'
                ],
                [
                    'event_type' => 'dangerous_quest',
                    'required_trait_code' => 'ambitious',
                    'response_type' => 'accept_quest',
                    'probability' => 0.9,
                    'influence_modifier' => 1.3,
                    'description' => 'Ambitious heroes drawn to challenging quests'
                ]
            ]
        ];
    }
}
