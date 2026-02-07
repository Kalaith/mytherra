<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return [
    'up' => function () {
        if (Capsule::schema()->hasColumn('regions', 'divine_resonance')) {
            return;
        }
        
        Capsule::schema()->table('regions', function ($table) {
            $table->integer('divine_resonance')->default(50);
            $table->index('divine_resonance');
        });
    },
    'down' => function () {
        if (Capsule::schema()->hasColumn('regions', 'divine_resonance')) {
            Capsule::schema()->table('regions', function ($table) {
                $table->dropColumn('divine_resonance');
            });
        }
    }
];
