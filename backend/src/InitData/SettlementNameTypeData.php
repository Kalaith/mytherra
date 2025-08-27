<?php

namespace App\InitData;

class SettlementNameTypeData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'type-prefix',
                'name' => 'Prefix',
                'code' => 'prefix',
                'description' => 'First part of the settlement name',
                'is_active' => true
            ],
            [
                'id' => 'type-suffix',
                'name' => 'Suffix',
                'code' => 'suffix',
                'description' => 'Last part of the settlement name',
                'is_active' => true
            ],
            [
                'id' => 'type-special',
                'name' => 'Special',
                'code' => 'special',
                'description' => 'Special full settlement names',
                'is_active' => true
            ]
        ];
    }
}
