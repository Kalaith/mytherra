<?php

namespace App\InitData;

class BuildingStatusData
{
    public static function getData(): array
    {
        return [
            [
                'id' => 'status-active',
                'name' => 'Active',
                'code' => 'active',
                'description' => 'Building is fully operational and functioning normally',
                'productivity_modifier' => 1.0,
                'maintenance_modifier' => 1.0,
                'is_active' => true
            ],
            [
                'id' => 'status-abandoned',
                'name' => 'Abandoned',
                'code' => 'abandoned',
                'description' => 'Building has been left empty and unmaintained',
                'productivity_modifier' => 0.0,
                'maintenance_modifier' => 0.5,
                'is_active' => true
            ],
            [
                'id' => 'status-corrupted',
                'name' => 'Corrupted',
                'code' => 'corrupted',
                'description' => 'Building has been tainted by dark magic or evil influence',
                'productivity_modifier' => 0.3,
                'maintenance_modifier' => 1.5,
                'is_active' => true
            ],
            [
                'id' => 'status-ruined',
                'name' => 'Ruined',
                'code' => 'ruined',
                'description' => 'Building has been severely damaged and is mostly unusable',
                'productivity_modifier' => 0.1,
                'maintenance_modifier' => 2.0,
                'is_active' => true
            ],
            [
                'id' => 'status-blessed',
                'name' => 'Blessed',
                'code' => 'blessed',
                'description' => 'Building has been blessed by divine forces',
                'productivity_modifier' => 1.3,
                'maintenance_modifier' => 0.8,
                'is_active' => true
            ],
            [
                'id' => 'status-under-construction',
                'name' => 'Under Construction',
                'code' => 'under_construction',
                'description' => 'Building is currently being built',
                'productivity_modifier' => 0.0,
                'maintenance_modifier' => 0.0,
                'is_active' => true
            ],
            [
                'id' => 'status-renovating',
                'name' => 'Renovating',
                'code' => 'renovating',
                'description' => 'Building is undergoing renovation or upgrade',
                'productivity_modifier' => 0.5,
                'maintenance_modifier' => 1.2,
                'is_active' => true
            ],
            [
                'id' => 'status-haunted',
                'name' => 'Haunted',
                'code' => 'haunted',
                'description' => 'Building is inhabited by supernatural entities',
                'productivity_modifier' => 0.6,
                'maintenance_modifier' => 1.4,
                'is_active' => true
            ],
            [
                'id' => 'status-enchanted',
                'name' => 'Enchanted',
                'code' => 'enchanted',
                'description' => 'Building is enhanced by beneficial magic',
                'productivity_modifier' => 1.4,
                'maintenance_modifier' => 0.7,
                'is_active' => true
            ],
            [
                'id' => 'status-quarantined',
                'name' => 'Quarantined',
                'code' => 'quarantined',
                'description' => 'Building has been sealed off due to disease or danger',
                'productivity_modifier' => 0.0,
                'maintenance_modifier' => 1.5,
                'is_active' => true
            ]
        ];
    }
}
