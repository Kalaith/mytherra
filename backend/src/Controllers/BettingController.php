<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Actions\BettingActions;
use App\Traits\ApiResponseTrait;
use App\Utils\Logger;

class BettingController
{
    use ApiResponseTrait;

    public function __construct(
        private BettingActions $bettingActions
    ) {}

    /**
     * Create a new divine bet
     * POST /api/bets
     */    public function placeDivineBet(Request $request, Response $response): Response
    {
        $body = json_decode($request->getBody(), true);
        
        // Validate required fields
        $requiredFields = ['betType', 'targetId', 'description', 'timeframe', 'confidence', 'divineFavorStake'];
        foreach ($requiredFields as $field) {
            if (!isset($body[$field])) {
                return $this->jsonResponse($response, [
                    'success' => false,
                    'error' => [
                        'message' => "Missing required field: {$field}",
                        'code' => 'VALIDATION_ERROR'
                    ]
                ], 400);
            }
        }
        
        // Validate bet type
        $validBetTypes = [
            'settlement_growth', 'landmark_discovery', 'cultural_shift', 
            'hero_settlement_bond', 'hero_location_visit', 'settlement_transformation', 
            'corruption_spread'
        ];
        if (!in_array($body['betType'], $validBetTypes)) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => [
                    'message' => 'Invalid bet type',
                    'code' => 'VALIDATION_ERROR'
                ]
            ], 400);
        }
        
        // Validate confidence level
        $validConfidenceLevels = ['long_shot', 'possible', 'likely', 'near_certain'];
        if (!in_array($body['confidence'], $validConfidenceLevels)) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => [
                    'message' => 'Invalid confidence level',
                    'code' => 'VALIDATION_ERROR'
                ]
            ], 400);
        }
        
        return $this->handleApiAction(
            $response,
            fn() => $this->bettingActions->placeDivineBet($body),
            'placing divine bet',
            'Failed to place divine bet'
        );
    }    /**
     * Get divine bet by ID
     * GET /api/bets/:id
     */    public function getDivineBetById(Request $request, Response $response, array $args): Response
    {
        return $this->handleApiAction(
            $response,
            fn() => $this->bettingActions->fetchDivineBetById($args['id']),
            'fetching divine bet',
            'Divine bet not found'
        );
    }

    /**
     * Get all divine bets
     * GET /api/bets
     */
    public function getAllDivineBets(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $filters = [
            'status' => $queryParams['status'] ?? null,
            'betType' => $queryParams['betType'] ?? null,
            'targetId' => $queryParams['targetId'] ?? null,
            'limit' => isset($queryParams['limit']) ? min((int)$queryParams['limit'], 100) : 20,
            'offset' => isset($queryParams['offset']) ? (int)$queryParams['offset'] : 0
        ];
        
        return $this->handleApiAction(
            $response,
            fn() => $this->bettingActions->fetchAllDivineBets($filters),
            'fetching divine bets',
            'No divine bets found'
        );
    }    /**
     * Get betting odds
     * GET /api/betting-odds
     */
    public function getBettingOdds(Request $request, Response $response): Response
    {
        return $this->handleApiAction(
            $response,
            fn() => $this->bettingActions->fetchBettingOdds(),
            'calculating betting odds',
            'Failed to calculate betting odds'
        );
    }

    /**
     * Get speculation events
     * GET /api/speculation-events
     */
    public function getSpeculationEvents(Request $request, Response $response): Response
    {
        return $this->handleApiAction(
            $response,
            fn() => $this->bettingActions->fetchSpeculationEvents(),
            'fetching speculation events',
            'No speculation events found'
        );
    }

    /**
     * Process expired bets (Admin endpoint)
     * POST /api/admin/process-expired-bets
     */
    public function processExpiredBets(Request $request, Response $response): Response
    {
        return $this->handleApiAction(
            $response,
            fn() => $this->bettingActions->processExpiredBets(),
            'processing expired bets',
            'Failed to process expired bets'
        );
    }
}
