<?php

namespace App\External;

use Exception;
use App\Utils\Logger;
use App\Models\DivineBet;
use App\Models\Settlement;
use App\Models\Hero;
use App\Models\Region;
use App\Models\Landmark;

class BettingRepository
{
    /**
     * Insert a new divine bet into the database
     */
    public function createDivineBet($betData)
    {
        try {
            // Generate ID if not provided
            if (!isset($betData['id'])) {
                $betData['id'] = 'bet-' . uniqid();
            }

            $bet = DivineBet::create($betData);
            return $bet->id;
        } catch (Exception $e) {
            Logger::error("Error creating divine bet: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create bet (alias for createDivineBet)
     */
    public function createBet($betData)
    {
        return $this->createDivineBet($betData);
    }/**
     * Fetch divine bet by ID
     */
    public function fetchDivineBetById($id)
    {
        try {
            $bet = DivineBet::find($id);
            
            if (!$bet) {
                return null;
            }
            
            return $this->transformBetData($bet->toArray());
        } catch (Exception $e) {
            Logger::error("Error fetching bet {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch all divine bets with filtering
     */
    public function fetchAllDivineBets($filters = [], $limit = 20, $offset = 0)
    {
        try {
            $query = DivineBet::query();
            
            // Apply filters
            if (!empty($filters['playerId'])) {
                $query->where('player_id', $filters['playerId']);
            }
            
            if (!empty($filters['betType'])) {
                $query->where('bet_type', $filters['betType']);
            }
            
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            if (!empty($filters['targetId'])) {
                $query->where('target_id', $filters['targetId']);
            }
            
            if (!empty($filters['confidence'])) {
                $query->where('confidence', $filters['confidence']);
            }
            
            if (!empty($filters['timeframe'])) {
                $query->where('timeframe', $filters['timeframe']);
            }
            
            if (!empty($filters['minStake'])) {
                $query->where('divine_favor_stake', '>=', $filters['minStake']);
            }
            
            if (!empty($filters['maxStake'])) {
                $query->where('divine_favor_stake', '<=', $filters['maxStake']);
            }
            
            // Add ordering and pagination
            $bets = $query->orderBy('created_at', 'desc')
                         ->skip($offset)
                         ->take($limit)
                         ->get();
            
            return $this->transformBetDataArray($bets->toArray());
            
        } catch (Exception $e) {
            Logger::error("Error fetching divine bets: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if a target entity exists
     */
    public function validateTargetEntity($targetId)
    {
        // Check in settlements
        if (Settlement::where('id', $targetId)->exists()) return true;
        
        // Check in heroes
        if (Hero::where('id', $targetId)->exists()) return true;
        
        // Check in regions
        if (Region::where('id', $targetId)->exists()) return true;
        
        // Check in landmarks
        if (Landmark::where('id', $targetId)->exists()) return true;
        
        return false;
    }

    /**
     * Get sample targets for odds calculation
     */
    public function getSampleTargets()
    {
        $targets = [];
        
        // Get sample settlement
        $settlement = Settlement::first();
        if ($settlement) $targets['settlement'] = $settlement->id;
        
        // Get sample hero
        $hero = Hero::first();
        if ($hero) $targets['hero'] = $hero->id;
        
        // Get sample region
        $region = Region::first();
        if ($region) $targets['region'] = $region->id;
        
        // Get sample landmark
        $landmark = Landmark::first();
        if ($landmark) $targets['landmark'] = $landmark->id;
        
        return $targets;
    }

    /**
     * Get sample regions for speculation events
     */
    public function getSampleRegions($limit = 5)
    {
        return Region::select('id')->take($limit)->get()->toArray();
    }

    /**
     * Alias for fetchDivineBetById to match BettingActions interface
     */
    public function getBetById($id)
    {
        return $this->fetchDivineBetById($id);
    }

    /**
     * Alias for fetchAllDivineBets to match BettingActions interface
     */
    public function getAllBets($filters = [])
    {
        $limit = $filters['limit'] ?? 20;
        $offset = $filters['offset'] ?? 0;
        unset($filters['limit'], $filters['offset']);
        
        return $this->fetchAllDivineBets($filters, $limit, $offset);
    }

    /**
     * Get speculation events for betting opportunities
     */
    public function getSpeculationEvents($filters = [])
    {
        // For now, return a mock response since this would need complex game logic
        // In a real implementation, this would generate events based on current game state
        return [
            [
                'id' => 'event-001',
                'title' => 'The Rise of a New Settlement',
                'description' => 'A small hamlet shows signs of unprecedented growth. Will it become a thriving city?',
                'type' => 'settlement_growth',
                'status' => 'active',
                'regionId' => 'region-001',
                'targetId' => 'settlement-001',
                'timeframe' => ['minimum' => 2, 'maximum' => 8],
                'bettingOptions' => [
                    [
                        'id' => 'option-001',
                        'description' => 'Settlement becomes a major trade hub',
                        'currentOdds' => 2.5,
                        'minimumStake' => 15,
                        'potentialPayout' => 37
                    ]
                ]
            ]
        ];
    }

    /**
     * Process expired bets and update their status
     */
    public function processExpiredBets($currentYear)
    {
        try {
            // Find expired bets
            $expiredBets = DivineBet::where('status', 'active')
                ->whereRaw('(placed_year + timeframe) <= ?', [$currentYear])
                ->get();

            $processedCount = 0;
            foreach ($expiredBets as $bet) {
                // Mark as expired
                $bet->update([
                    'status' => 'expired',
                    'resolved_year' => $currentYear,
                    'resolution_notes' => 'Bet expired without resolution'
                ]);
                $processedCount++;
            }

            return [
                'processed' => $processedCount,
                'expired_bets' => $expiredBets->toArray()
            ];        } catch (Exception $e) {
            Logger::error("Error processing expired bets: " . $e->getMessage());
            throw $e;
        }
    }    /**
     * Transform database row to API format (snake_case to camelCase)
     */
    private function transformBetData($bet)
    {
        if (!$bet) {
            return null;
        }

        try {
            $transformed = [
                'id' => $bet['id'],
                'playerId' => $bet['player_id'],
                'betType' => $bet['bet_type'],
                'targetId' => $bet['target_id'],
                'description' => $bet['description'],
                'timeframe' => (int)$bet['timeframe'],
                'confidence' => $bet['confidence'],
                'divineFavorStake' => (int)$bet['divine_favor_stake'],
                'potentialPayout' => (int)$bet['potential_payout'],
                'currentOdds' => (float)$bet['current_odds'],
                'status' => $bet['status'],
                'placedYear' => (int)$bet['placed_year'],
                'resolvedYear' => $bet['resolved_year'] ? (int)$bet['resolved_year'] : null,
                'resolutionNotes' => $bet['resolution_notes'],
                'createdAt' => $bet['created_at'],
                'updatedAt' => $bet['updated_at']
            ];
            
            return $transformed;
        } catch (Exception $e) {
            Logger::error("Error in transformBetData: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Transform array of database rows to API format
     */
    private function transformBetDataArray($bets)
    {
        if (!$bets) {
            return [];
        }

        return array_map([$this, 'transformBetData'], $bets);
    }
}
