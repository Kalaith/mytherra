<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return [
    'up' => function () {
        if (!Capsule::schema()->hasTable('influence_history')) {
            Capsule::schema()->create('influence_history', function ($table) {
                $table->id();
                $table->unsignedBigInteger('player_id');
                $table->string('target_type'); // e.g., 'region', 'character', 'settlement'
                $table->unsignedBigInteger('target_id');
                $table->string('influence_type');
                $table->integer('divine_favor_spent');
                $table->decimal('effect_magnitude', 10, 2);
                $table->json('modifiers')->nullable();
                $table->timestamp('applied_at')->useCurrent();
                $table->timestamp('expires_at')->nullable();
                
                $table->foreign('player_id')->references('id')->on('players');
                $table->index(['target_type', 'target_id']);
                $table->index('applied_at');
            });
        }
    },
    'down' => function () {
        Capsule::schema()->dropIfExists('influence_history');
    }
];
