<?php

namespace App\InitData;

class BetTargetModifierData
{
    public static function getData(): array
    {
        return [
            // Settlement Growth Modifiers
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_growth',
                'condition_field' => 'prosperity',
                'condition_value' => 80,
                'comparison_operator' => '>',
                'modifier_value' => 0.7,
                'modifier_type' => 'multiply',
                'description' => 'High prosperity settlements are more likely to grow'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_growth',
                'condition_field' => 'prosperity',
                'condition_value' => 60,
                'comparison_operator' => '>',
                'modifier_value' => 0.8,
                'modifier_type' => 'multiply',
                'description' => 'Above average prosperity settlements have good growth potential'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_growth',
                'condition_field' => 'prosperity',
                'condition_value' => 30,
                'comparison_operator' => '<',
                'modifier_value' => 1.5,
                'modifier_type' => 'multiply',
                'description' => 'Low prosperity settlements struggle to grow'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_growth',
                'condition_field' => 'population',
                'condition_value' => 5000,
                'comparison_operator' => '>',
                'modifier_value' => 1.2,
                'modifier_type' => 'multiply',
                'description' => 'Larger settlements grow more slowly'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_growth',
                'condition_field' => 'status',
                'condition_value' => 0,
                'comparison_operator' => '=',
                'modifier_value' => 2.0,
                'modifier_type' => 'multiply',
                'description' => 'Declining settlements are unlikely to grow'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_growth',
                'condition_field' => 'status',
                'condition_value' => 1,
                'comparison_operator' => '=',
                'modifier_value' => 0.6,
                'modifier_type' => 'multiply',
                'description' => 'Thriving settlements are likely to continue growing'
            ],

            // Settlement Transformation Modifiers
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_transformation',
                'condition_field' => 'prosperity',
                'condition_value' => 90,
                'comparison_operator' => '>',
                'modifier_value' => 0.8,
                'modifier_type' => 'multiply',
                'description' => 'Extremely prosperous settlements can transform'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_transformation',
                'condition_field' => 'prosperity',
                'condition_value' => 10,
                'comparison_operator' => '<',
                'modifier_value' => 0.8,
                'modifier_type' => 'multiply',
                'description' => 'Struggling settlements might transform'
            ],
            [
                'target_type' => 'settlement',
                'bet_type' => 'settlement_transformation',
                'condition_field' => 'status',
                'condition_value' => 2,
                'comparison_operator' => '=',
                'modifier_value' => 1.5,
                'modifier_type' => 'multiply',
                'description' => 'Stable settlements rarely transform'
            ],

            // Hero Settlement Bond Modifiers
            [
                'target_type' => 'hero',
                'bet_type' => 'hero_settlement_bond',
                'condition_field' => 'charisma',
                'condition_value' => 50,
                'comparison_operator' => '>',
                'modifier_value' => 0.01,
                'modifier_type' => 'add',
                'description' => 'Charismatic heroes form bonds more easily'
            ],
            [
                'target_type' => 'hero',
                'bet_type' => 'hero_settlement_bond',
                'condition_field' => 'status',
                'condition_value' => 1,
                'comparison_operator' => '=',
                'modifier_value' => 0.8,
                'modifier_type' => 'multiply',
                'description' => 'Active heroes are more likely to form bonds'
            ],
            [
                'target_type' => 'hero',
                'bet_type' => 'hero_settlement_bond',
                'condition_field' => 'status',
                'condition_value' => 2,
                'comparison_operator' => '=',
                'modifier_value' => 1.2,
                'modifier_type' => 'multiply',
                'description' => 'Resting heroes are less likely to form bonds'
            ],

            // Hero Location Visit Modifiers
            [
                'target_type' => 'hero',
                'bet_type' => 'hero_location_visit',
                'condition_field' => 'exploration_drive',
                'condition_value' => 50,
                'comparison_operator' => '>',
                'modifier_value' => 0.01,
                'modifier_type' => 'add',
                'description' => 'Adventurous heroes travel more'
            ],
            [
                'target_type' => 'hero',
                'bet_type' => 'hero_location_visit',
                'condition_field' => 'level',
                'condition_value' => 5,
                'comparison_operator' => '>',
                'modifier_value' => 0.9,
                'modifier_type' => 'multiply',
                'description' => 'Higher level heroes travel more efficiently'
            ],

            // Region Cultural Shift Modifiers
            [
                'target_type' => 'region',
                'bet_type' => 'cultural_shift',
                'condition_field' => 'chaos',
                'condition_value' => 50,
                'comparison_operator' => '>',
                'modifier_value' => 0.01,
                'modifier_type' => 'add',
                'description' => 'Chaotic regions experience more cultural shifts'
            ],
            [
                'target_type' => 'region',
                'bet_type' => 'cultural_shift',
                'condition_field' => 'divine_resonance',
                'condition_value' => 70,
                'comparison_operator' => '>',
                'modifier_value' => 0.8,
                'modifier_type' => 'multiply',
                'description' => 'Divine presence stabilizes culture'
            ],

            // Region Corruption Spread Modifiers
            [
                'target_type' => 'region',
                'bet_type' => 'corruption_spread',
                'condition_field' => 'chaos',
                'condition_value' => 50,
                'comparison_operator' => '>',
                'modifier_value' => 0.015,
                'modifier_type' => 'add',
                'description' => 'Chaotic regions are more susceptible to corruption'
            ],
            [
                'target_type' => 'region',
                'bet_type' => 'corruption_spread',
                'condition_field' => 'magic_affinity',
                'condition_value' => 50,
                'comparison_operator' => '>',
                'modifier_value' => 0.01,
                'modifier_type' => 'add',
                'description' => 'Magical regions attract corruption'
            ]
        ];
    }
}
