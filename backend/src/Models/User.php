<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Schema;

class User extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'auth_user_id',
        'auth0_id',
        'auth_email',
        'email',
        'auth_username',
        'username',
        'display_name',
        'divine_influence',
        'divine_favor',
        'level',
        'experience',
        'character_class',
        'guild_id',
        'guild_rank',
        'betting_stats',
        'game_preferences',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'auth_user_id' => 'integer',
        'divine_influence' => 'integer',
        'divine_favor' => 'integer',
        'level' => 'integer',
        'experience' => 'integer',
        'betting_stats' => 'json',
        'game_preferences' => 'json',
        'is_active' => 'boolean'
    ];

    public static function createTable()
    {
        if (!Schema::schema()->hasTable('users')) {
            Schema::schema()->create('users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('auth_user_id')->nullable()->index();
                $table->string('auth0_id')->nullable()->index();
                $table->string('auth_email')->nullable();
                $table->string('email')->nullable();
                $table->string('auth_username')->nullable();
                $table->string('username')->nullable();
                $table->string('display_name')->nullable();
                $table->integer('divine_influence')->default(100);
                $table->integer('divine_favor')->default(100);
                $table->integer('level')->default(1);
                $table->integer('experience')->default(0);
                $table->string('character_class')->default('novice');
                $table->unsignedBigInteger('guild_id')->nullable();
                $table->string('guild_rank')->nullable();
                $table->json('betting_stats')->nullable();
                $table->json('game_preferences')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('auth_user_id', 'idx_auth_user_id');
                $table->index('auth0_id', 'idx_auth0_id');
            });
        }
    }

    /**
     * Create or update a user from auth portal data
     */
    public static function createOrUpdateFromAuthData(array $authData): self
    {
        $user = self::where('auth_user_id', $authData['user_id'])->first();
        
        if (!$user) {
            $user = new self();
            $user->auth_user_id = $authData['user_id'];
            $user->divine_influence = 100; // Starting influence
            $user->divine_favor = 100; // Starting favor
            $user->betting_stats = [];
            $user->game_preferences = [];
        }
        
        // Update auth portal data
        $user->auth_email = $authData['email'] ?? $user->auth_email;
        $user->auth_username = $authData['username'] ?? $user->auth_username ?? $authData['email'] ?? 'user_' . $authData['user_id'];
        $user->display_name = $authData['display_name'] ?? $authData['username'] ?? $user->display_name ?? $user->auth_username;
        $user->is_active = true;
        
        $user->save();
        
        return $user;
    }

    /**
     * Add divine influence
     */
    public function addDivineInfluence(int $amount): bool
    {
        $this->divine_influence += $amount;
        return $this->save();
    }

    /**
     * Spend divine influence
     */
    public function spendDivineInfluence(int $amount): bool
    {
        if ($this->divine_influence < $amount) {
            return false;
        }
        $this->divine_influence -= $amount;
        return $this->save();
    }

    /**
     * Add divine favor
     */
    public function addDivineFavor(int $amount): bool
    {
        $this->divine_favor += $amount;
        return $this->save();
    }

    /**
     * Spend divine favor
     */
    public function spendDivineFavor(int $amount): bool
    {
        if ($this->divine_favor < $amount) {
            return false;
        }
        $this->divine_favor -= $amount;
        return $this->save();
    }

    /**
     * Get divine influence
     */
    public function getDivineInfluence(): int
    {
        return $this->divine_influence;
    }

    /**
     * Get divine favor
     */
    public function getDivineFavor(): int
    {
        return $this->divine_favor;
    }

    /**
     * Update betting statistics
     */
    public function updateBettingStats(array $stats): bool
    {
        $currentStats = $this->betting_stats ?? [];
        $this->betting_stats = array_merge($currentStats, $stats);
        return $this->save();
    }

    /**
     * Update game preferences
     */
    public function updateGamePreferences(array $preferences): bool
    {
        $currentPreferences = $this->game_preferences ?? [];
        $this->game_preferences = array_merge($currentPreferences, $preferences);
        return $this->save();
    }

    /**
     * Add experience and handle level ups (MMO feature)
     */
    public function addExperience(int $amount): bool
    {
        $this->experience += $amount;
        
        // Simple level calculation - level up every 1000 XP
        $newLevel = intval($this->experience / 1000) + 1;
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
        }
        
        return $this->save();
    }

    /**
     * Get level progress (percentage to next level)
     */
    public function getLevelProgress(): float
    {
        $currentLevelXP = ($this->level - 1) * 1000;
        $nextLevelXP = $this->level * 1000;
        $progressXP = $this->experience - $currentLevelXP;
        
        return ($progressXP / 1000) * 100;
    }

    /**
     * Join a guild (MMO feature)
     */
    public function joinGuild(int $guildId, string $rank = 'member'): bool
    {
        $this->guild_id = $guildId;
        $this->guild_rank = $rank;
        return $this->save();
    }

    /**
     * Leave guild (MMO feature)
     */
    public function leaveGuild(): bool
    {
        $this->guild_id = null;
        $this->guild_rank = null;
        return $this->save();
    }

    /**
     * Promote in guild (MMO feature)
     */
    public function promoteInGuild(string $newRank): bool
    {
        if ($this->guild_id) {
            $this->guild_rank = $newRank;
            return $this->save();
        }
        return false;
    }

    /**
     * Get character power level (based on level, divine influence, and favor)
     */
    public function getPowerLevel(): int
    {
        return ($this->level * 100) + $this->divine_influence + $this->divine_favor;
    }

    /**
     * Get character rank based on power level
     */
    public function getCharacterRank(): string
    {
        $powerLevel = $this->getPowerLevel();
        
        if ($powerLevel >= 10000) return 'Divine Champion';
        if ($powerLevel >= 5000) return 'Legendary Hero';
        if ($powerLevel >= 2500) return 'Epic Adventurer';
        if ($powerLevel >= 1000) return 'Veteran Explorer';
        if ($powerLevel >= 500) return 'Skilled Adventurer';
        if ($powerLevel >= 200) return 'Novice Explorer';
        
        return 'Apprentice';
    }
}
