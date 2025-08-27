<?php

namespace App\InitData;

class HeroDeathReasonData
{
    public static function getData(): array
    {
        return [
            [
                'code' => 'glorious_battle',
                'description' => 'Fell in glorious battle',
                'category' => 'combat',
                'severity' => 4
            ],
            [
                'code' => 'lost_wilderness',
                'description' => 'Lost to the wilderness',
                'category' => 'tragic',
                'severity' => 3
            ],
            [
                'code' => 'dark_magic',
                'description' => 'Claimed by dark magic',
                'category' => 'magical',
                'severity' => 5
            ],
            [
                'code' => 'treachery',
                'description' => 'Victim of treachery',
                'category' => 'tragic',
                'severity' => 4
            ],
            [
                'code' => 'exploring_ruins',
                'description' => 'Lost exploring ancient ruins',
                'category' => 'tragic',
                'severity' => 3
            ],
            [
                'code' => 'mysterious_illness',
                'description' => 'Succumbed to a mysterious illness',
                'category' => 'natural',
                'severity' => 2
            ],
            [
                'code' => 'vanished',
                'description' => 'Vanished without a trace',
                'category' => 'mysterious',
                'severity' => 3
            ],
            [
                'code' => 'heroic_sacrifice',
                'description' => 'Met their end in a heroic sacrifice',
                'category' => 'combat',
                'severity' => 5
            ]
        ];
    }
}
