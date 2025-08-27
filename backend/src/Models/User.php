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
        'auth_email',
        'auth_username',
        'display_name',
        'divine_influence',
        'divine_favor',
        'betting_stats',
        'game_preferences',
        'is_active'
    ];

    protected $casts = [
        'auth_user_id' => 'integer',
        'divine_influence' => 'integer',
        'divine_favor' => 'integer',
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
                $table->string('auth_email')->nullable();
                $table->string('auth_username')->nullable();
                $table->string('display_name')->nullable();
                $table->integer('divine_influence')->default(100);
                $table->integer('divine_favor')->default(100);
                $table->json('betting_stats')->nullable();
                $table->json('game_preferences')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('auth_user_id', 'idx_auth_user_id');
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
}
