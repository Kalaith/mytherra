<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Schema;

class BetTypeConfig extends Model
{
    protected $table = 'bet_type_configs';
    
    protected $fillable = [
        'code',
        'description',
        'base_odds',
        'resolve_conditions',
        'is_active'
    ];

    protected $casts = [
        'base_odds' => 'float',
        'is_active' => 'boolean'
    ];

    public static function createTable()
    {
        if (!Schema::schema()->hasTable('bet_type_configs')) {
            Schema::schema()->create('bet_type_configs', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('description');
                $table->decimal('base_odds', 4, 2);
                $table->string('resolve_conditions');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('code');
                $table->index('is_active');
            });
        }
    }
}

class BetConfidenceConfig extends Model
{
    protected $table = 'bet_confidence_configs';
    
    protected $fillable = [
        'code',
        'description',
        'odds_modifier',
        'stake_multiplier',
        'is_active'
    ];

    protected $casts = [
        'odds_modifier' => 'float',
        'stake_multiplier' => 'float',
        'is_active' => 'boolean'
    ];

    public static function createTable()
    {
        if (!Schema::schema()->hasTable('bet_confidence_configs')) {
            Schema::schema()->create('bet_confidence_configs', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('description');
                $table->decimal('odds_modifier', 4, 2);
                $table->decimal('stake_multiplier', 4, 2);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('code');
                $table->index('is_active');
            });
        }
    }
}

class BetTimeframeModifier extends Model
{
    protected $table = 'bet_timeframe_modifiers';
    
    protected $fillable = [
        'max_timeframe',
        'modifier'
    ];

    protected $casts = [
        'max_timeframe' => 'integer',
        'modifier' => 'float'
    ];

    public static function createTable()
    {
        if (!Schema::schema()->hasTable('bet_timeframe_modifiers')) {
            Schema::schema()->create('bet_timeframe_modifiers', function (Blueprint $table) {
                $table->id();
                $table->integer('max_timeframe');
                $table->decimal('modifier', 4, 2);
                $table->timestamps();

                $table->index('max_timeframe');
            });
        }
    }
}

class BetTargetModifier extends Model
{
    protected $table = 'bet_target_modifiers';
    
    protected $fillable = [
        'target_type',
        'bet_type',
        'condition_field',
        'condition_value',
        'comparison_operator',
        'modifier_value',
        'modifier_type'
    ];

    protected $casts = [
        'condition_value' => 'float',
        'modifier_value' => 'float'
    ];

    public static function createTable()
    {
        if (!Schema::schema()->hasTable('bet_target_modifiers')) {
            Schema::schema()->create('bet_target_modifiers', function (Blueprint $table) {
                $table->id();
                $table->string('target_type'); // e.g., settlement, hero, region, landmark
                $table->string('bet_type');
                $table->string('condition_field'); // e.g., prosperity, level, chaos
                $table->decimal('condition_value', 8, 2);
                $table->string('comparison_operator'); // e.g., >, <, =, >=, <=
                $table->decimal('modifier_value', 4, 2);
                $table->enum('modifier_type', ['multiply', 'add'])->default('multiply');
                $table->timestamps();

                $table->index(['target_type', 'bet_type']);
            });
        }
    }
}
