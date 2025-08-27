<?php

namespace App\External;

use Exception;
use App\Utils\Logger;
use App\Models\Settlement;

class SettlementRepository
{
    /**
     * Fetch settlement by ID
     */
    public function getById($id)
    {
        try {
            $settlement = Settlement::find($id);
            return $settlement ? $settlement->toArray() : false;
        } catch (Exception $e) {
            Logger::error("Error fetching settlement by ID: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch settlement by ID
     */
    public function getSettlementById($id)
    {
        return $this->getById($id);
    }

    /**
     * Fetch multiple settlements by array of IDs
     */
    public function getSettlementsByIds(array $ids)
    {
        try {
            return Settlement::whereIn('id', $ids)->get()->map->toArray()->all();
        } catch (Exception $e) {
            Logger::error("Error fetching settlements by IDs: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all settlements with optional filtering
     */
    public function getAllSettlements($filters = [], $limit = 20, $offset = 0)
    {
        try {
            $query = Settlement::query();
            
            // Apply filters using Eloquent
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }
            
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            if (!empty($filters['region_id'])) {
                $query->where('region_id', $filters['region_id']);
            }
            
            if (!empty($filters['minPopulation'])) {
                $query->where('population', '>=', $filters['minPopulation']);
            }
            
            if (!empty($filters['maxPopulation'])) {
                $query->where('population', '<=', $filters['maxPopulation']);
            }
            
            if (!empty($filters['minProsperity'])) {
                $query->where('prosperity', '>=', $filters['minProsperity']);
            }
            
            if (!empty($filters['maxProsperity'])) {
                $query->where('prosperity', '<=', $filters['maxProsperity']);
            }
            
            if (!empty($filters['hasPort'])) {
                $query->where('has_port', $filters['hasPort']);
            }
            
            if (!empty($filters['tradeRoutes'])) {
                $query->where('trade_routes', '>=', $filters['tradeRoutes']);
            }

            // Apply ordering and pagination
            $settlements = $query
                ->orderBy('name', 'ASC')
                ->skip($offset)
                ->take($limit)
                ->get();
            
            return $settlements->map->toArray()->all();
        } catch (Exception $e) {
            Logger::error("Error fetching settlements: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Save or update a settlement
     */
    public function saveSettlement($settlementData)
    {
        try {
            $requiredFields = ['name', 'type', 'region_id', 'population', 'status'];
            
            // Add timestamps
            $settlementData['created_at'] = $settlementData['created_at'] ?? date('Y-m-d H:i:s');
            $settlementData['updated_at'] = date('Y-m-d H:i:s');

            return $this->saveEntity($settlementData, $requiredFields);
        } catch (Exception $e) {
            Logger::error("Error saving settlement: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get settlements suitable for betting opportunities
     */
    public function getSettlementsForBetting($filters = [])
    {
        $sql = "SELECT * FROM settlements WHERE 1=1";
        $params = [];

        // Add filters for settlements that are more likely to have interesting events
        $sql .= " AND (
            prosperity >= 50 OR
            status IN ('growing', 'declining', 'transforming') OR
            cultural_influence >= 60
        )";

        if (!empty($filters['minProsperity'])) {
            $sql .= " AND prosperity >= :min_prosperity";
            $params[':min_prosperity'] = $filters['minProsperity'];
        }

        if (!empty($filters['type'])) {
            $sql .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['regionId'])) {
            $sql .= " AND region_id = :region_id";
            $params[':region_id'] = $filters['regionId'];
        }

        // Order by prosperity and population to find most notable settlements
        $sql .= " ORDER BY prosperity DESC, population DESC";
        $sql .= " LIMIT :limit";
        $params[':limit'] = $filters['limit'] ?? 5;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    /**
     * Update settlement's population
     */
    public function updatePopulation($settlementId, $newPopulation)
    {
        try {
            if ($newPopulation < 0) {
                throw new Exception("Population cannot be negative");
            }
            
            $sql = "UPDATE {$this->table} SET 
                population = :population,
                updated_at = NOW()
                WHERE id = :id";
            
            return $this->executeQuery($sql, [
                ':id' => $settlementId,
                ':population' => $newPopulation
            ]);
        } catch (Exception $e) {
            Logger::error("Error updating settlement population: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update settlement's prosperity
     */
    public function updateProsperity($settlementId, $newProsperity)
    {
        try {
            if ($newProsperity < 0 || $newProsperity > 100) {
                throw new Exception("Prosperity must be between 0 and 100");
            }
            
            $sql = "UPDATE {$this->table} SET 
                prosperity = :prosperity,
                updated_at = NOW()
                WHERE id = :id";
            
            return $this->executeQuery($sql, [
                ':id' => $settlementId,
                ':prosperity' => $newProsperity
            ]);
        } catch (Exception $e) {
            Logger::error("Error updating settlement prosperity: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update settlement's status
     */
    public function updateStatus($settlementId, $newStatus)
    {

            $sql = "UPDATE {$this->table} SET 
                status = :status,
                updated_at = NOW()
            WHERE id = :id";
            
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $settlementId,
            ':status' => $newStatus
        ]);
    }

    /**
     * Update settlement's type (e.g., village to town)
     */
    public function updateType($settlementId, $newType)
    {
        $sql = "UPDATE settlements SET 
            type = :type,
            updated_at = NOW()
            WHERE id = :id";
            
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $settlementId,
            ':type' => $newType
        ]);
    }

    /**
     * Get all hero interactions with a settlement
     */
    public function getSettlementHeroInteractions($settlementId)
    {
        $sql = "SELECT * FROM hero_settlement_interactions 
                WHERE settlement_id = :settlement_id 
                ORDER BY interaction_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':settlement_id' => $settlementId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get settlements experiencing growth or transformation
     */
    public function getGrowingSettlements()
    {
        $sql = "SELECT * FROM settlements 
                WHERE status IN ('growing', 'transforming') 
                  AND prosperity >= 60
                ORDER BY prosperity DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}