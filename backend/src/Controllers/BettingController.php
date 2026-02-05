<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Actions\BettingActions;
use App\Services\ComboBetService;
use App\Models\DivineBet;
use App\Traits\ApiResponseTrait;
use App\Utils\Logger;

class BettingController
{
    use ApiResponseTrait;

    private ComboBetService $comboBetService;

    public function __construct(
        private BettingActions $bettingActions,
        ComboBetService $comboBetService
    ) {
        $this->comboBetService = $comboBetService;
    }

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
        
        // Validate bet type using model constant
        if (!DivineBet::validateBetType($body['betType'])) {
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

    /**
     * Create a combo bet
     * POST /api/combo-bets
     */
    public function createComboBet(Request $request, Response $response): Response
    {
        $body = json_decode($request->getBody(), true);
        
        if (!isset($body['betIds']) || !is_array($body['betIds'])) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => ['message' => 'betIds array is required']
            ], 400);
        }
        
        if (!isset($body['totalStake']) || !is_numeric($body['totalStake'])) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => ['message' => 'totalStake is required']
            ], 400);
        }
        
        try {
            $comboBet = $this->comboBetService->createComboBet(
                $body['betIds'],
                (int) $body['totalStake']
            );
            
            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $comboBet
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => ['message' => $e->getMessage()]
            ], 400);
        }
    }

    /**
     * Preview combo bet odds
     * POST /api/combo-bets/preview
     */
    public function previewComboBet(Request $request, Response $response): Response
    {
        $body = json_decode($request->getBody(), true);
        
        if (!isset($body['betIds']) || !is_array($body['betIds'])) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => ['message' => 'betIds array is required']
            ], 400);
        }
        
        try {
            $preview = $this->comboBetService->previewComboOdds($body['betIds']);
            
            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $preview
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => ['message' => $e->getMessage()]
            ], 400);
        }
    }

    /**
     * Get available bet types
     * GET /api/bet-types
     */
    public function getBetTypes(Request $request, Response $response): Response
    {
        return $this->jsonResponse($response, [
            'success' => true,
            'data' => DivineBet::BET_TYPE_CONFIGS
        ]);
    }
}
