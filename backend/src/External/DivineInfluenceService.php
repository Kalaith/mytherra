<?php

namespace App\External;

use PDO;
use Exception;
use App\Utils\Logger;

class DivineInfluenceService
{
    private DatabaseService $db;

    public function __construct(DatabaseService $db)
    {
        $this->db = $db;
    }

    /**
     * Calculate divine influence cost
     */
    public function calculateInfluenceCost(array $params): array
    {
        $baseCost = 100;
        $multiplier = 1.0;

        // Apply modifiers based on region's divine resonance if available
        if (isset($params['regionId'])) {
            $sql = "SELECT divine_resonance FROM regions WHERE id = ?";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute([$params['regionId']]);
            $resonance = $stmt->fetchColumn();
            
            if ($resonance !== false) {
                // Higher resonance means lower cost
                $multiplier *= (1 - ($resonance / 100) * 0.5);
            }
        }

        // Apply action type modifiers
        if (isset($params['actionType'])) {
            switch ($params['actionType']) {
                case 'bless':
                    $multiplier *= 1.2;
                    break;
                case 'corrupt':
                    $multiplier *= 1.5;
                    break;
                case 'guide':
                    $multiplier *= 0.8;
                    break;
                default:
                    break;
            }
        }

        $finalCost = round($baseCost * $multiplier);

        return [
            'baseCost' => $baseCost,
            'multiplier' => $multiplier,
            'finalCost' => $finalCost
        ];
    }

    /**
     * Apply divine influence to a target
     */
    public function applyInfluence(array $params): bool
    {
        try {
            $cost = $this->calculateInfluenceCost($params);
            
            // Here you would implement the actual influence application logic
            // For example, updating region or hero stats based on the influence type
            
            return true;
        } catch (Exception $e) {
            Logger::error("Error applying divine influence: " . $e->getMessage());
            throw $e;
        }
    }
}
