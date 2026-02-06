<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\BettingController;
use App\Controllers\BuildingController;
use App\Controllers\EventController;
use App\Controllers\ExportController;
use App\Controllers\HeroController;
use App\Controllers\InfluenceController;
use App\Controllers\LandmarkController;
use App\Controllers\RegionController;
use App\Controllers\SettlementController;
use App\Controllers\StatisticsController;
use App\Controllers\StatusController;
use App\Middleware\JwtAuthMiddleware;
use App\Middleware\AdminAuthMiddleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

return function (Router $router): void {
    $api = '/api';

    // Health/Status (Public)
    $router->get($api . '/site-status', function($request, $response) {
        $response->getBody()->write(json_encode(['status' => 'OK']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Auth Routes (Public)
    $router->get($api . '/auth/login-url', [AuthController::class, 'getLoginUrl']);
    $router->get($api . '/auth/register-url', [AuthController::class, 'getRegisterUrl']);
    $router->get($api . '/auth/callback', [AuthController::class, 'callback']);

    /**
     * Session Endpoint (Replacement for /auth/me)
     * Mirrors Blacksmith Forge implementation
     * Strict HS256 validation
     */
    $router->get($api . '/auth/session', function ($request, $response) {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            $queryParams = $request->getQueryParams();
            $token = $queryParams['token'] ?? null;
        }

        $secret = $_ENV['AUTH_PORTAL_JWT_SECRET'] ?? $_ENV['JWT_SECRET'] ?? '';
        
        if (!$token || !$secret) {
             $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Unauthorized'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            // Standardize user data structure
            $userId = $decoded->user_id ?? $decoded->sub ?? null;
            $email = $decoded->email ?? '';
            $role = $decoded->roles[0] ?? $decoded->role ?? 'user';
            $username = $decoded->username ?? ($email !== '' ? explode('@', $email)[0] : 'user');

            // Format response to match AuthContext expectations
            // AuthContext expects { data: { user: { ... } } }
            
            $userData = [
                'id' => (int) $userId,
                'email' => $email,
                'username' => $username,
                'role' => $role,
                // Add literal roles array if needed
                'roles' => $decoded->roles ?? [$role],
                'game_preferences' => $decoded->game_preferences ?? [],
            ];

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => [
                    'user' => $userData,
                    'profile' => $userData // Alias for convenience
                ],
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Invalid token: ' . $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    });

    // ===================================
    // PROTECTED ROUTES
    // ===================================

    // Auth Actions (Protected)
    $router->post($api . '/auth/logout', [AuthController::class, 'logout'], [JwtAuthMiddleware::class]);
    $router->put($api . '/auth/preferences', [AuthController::class, 'updatePreferences'], [JwtAuthMiddleware::class]);
    
    // For legacy support if frontend still calls /auth/me
    $router->get($api . '/auth/me', [AuthController::class, 'getCurrentUser'], [JwtAuthMiddleware::class]);

    // Region Routes
    $router->get($api . '/regions', [RegionController::class, 'getAllRegions'], [JwtAuthMiddleware::class]);
    $router->get($api . '/regions/{id}', [RegionController::class, 'getRegionById'], [JwtAuthMiddleware::class]);
    $router->get($api . '/regions/{id}/landmarks', [RegionController::class, 'getRegionLandmarks'], [JwtAuthMiddleware::class]);
    $router->post($api . '/regions', [RegionController::class, 'createRegion'], [JwtAuthMiddleware::class]);
    $router->post($api . '/regions/{id}/process', [RegionController::class, 'processRegionTick'], [JwtAuthMiddleware::class]);

    // Hero Routes
    $router->get($api . '/heroes', [HeroController::class, 'getAllHeroes'], [JwtAuthMiddleware::class]);
    $router->get($api . '/heroes/{id}', [HeroController::class, 'getHeroById'], [JwtAuthMiddleware::class]);

    // Settlement Routes
    $router->get($api . '/settlements', [SettlementController::class, 'getAllSettlements'], [JwtAuthMiddleware::class]);
    $router->get($api . '/settlements/{id}', [SettlementController::class, 'getSettlementById'], [JwtAuthMiddleware::class]);
    $router->get($api . '/settlements/{id}/buildings', [SettlementController::class, 'getSettlementBuildings'], [JwtAuthMiddleware::class]);

    // Event Routes
    $router->get($api . '/events', [EventController::class, 'getAllEvents'], [JwtAuthMiddleware::class]);
    $router->get($api . '/events/{id}', [EventController::class, 'getEventById'], [JwtAuthMiddleware::class]);

    // Building Routes
    $router->get($api . '/buildings', [BuildingController::class, 'getAllBuildings'], [JwtAuthMiddleware::class]);
    $router->get($api . '/buildings/{id}', [BuildingController::class, 'getBuildingById'], [JwtAuthMiddleware::class]);
    $router->post($api . '/buildings', [BuildingController::class, 'createBuilding'], [JwtAuthMiddleware::class]);
    $router->put($api . '/buildings/{id}', [BuildingController::class, 'updateBuilding'], [JwtAuthMiddleware::class]);
    $router->delete($api . '/buildings/{id}', [BuildingController::class, 'deleteBuilding'], [JwtAuthMiddleware::class]);

    // Landmark Routes
    $router->get($api . '/landmarks', [LandmarkController::class, 'getAllLandmarks'], [JwtAuthMiddleware::class]);
    $router->get($api . '/landmarks/{id}', [LandmarkController::class, 'getLandmarkById'], [JwtAuthMiddleware::class]);
    $router->post($api . '/landmarks', [LandmarkController::class, 'createLandmark'], [JwtAuthMiddleware::class]);
    $router->put($api . '/landmarks/{id}', [LandmarkController::class, 'updateLandmark'], [JwtAuthMiddleware::class]);
    $router->delete($api . '/landmarks/{id}', [LandmarkController::class, 'deleteLandmark'], [JwtAuthMiddleware::class]);
    $router->post($api . '/landmarks/{id}/discover', [LandmarkController::class, 'discoverLandmark'], [JwtAuthMiddleware::class]);

    // Betting Routes
    $router->post($api . '/bets', [BettingController::class, 'placeDivineBet'], [JwtAuthMiddleware::class]);
    $router->get($api . '/bets', [BettingController::class, 'getAllDivineBets'], [JwtAuthMiddleware::class]);
    $router->get($api . '/bets/{id}', [BettingController::class, 'getDivineBetById'], [JwtAuthMiddleware::class]);
    $router->get($api . '/speculation-events', [BettingController::class, 'getSpeculationEvents'], [JwtAuthMiddleware::class]);
    $router->get($api . '/betting-odds', [BettingController::class, 'getBettingOdds'], [JwtAuthMiddleware::class]);
    $router->get($api . '/bet-types', [BettingController::class, 'getBetTypes'], [JwtAuthMiddleware::class]);
    
    // Combo Betting Routes
    $router->post($api . '/combo-bets', [BettingController::class, 'createComboBet'], [JwtAuthMiddleware::class]);
    $router->post($api . '/combo-bets/preview', [BettingController::class, 'previewComboBet'], [JwtAuthMiddleware::class]);

    // Export Routes
    $router->get($api . '/export/types', [ExportController::class, 'getExportTypes'], [JwtAuthMiddleware::class]);
    $router->get($api . '/export/full', [ExportController::class, 'exportFull'], [JwtAuthMiddleware::class]);
    $router->get($api . '/export/{type}', [ExportController::class, 'exportByType'], [JwtAuthMiddleware::class]);

    // Influence Routes
    $router->post($api . '/influence/divine/calculate-cost', [InfluenceController::class, 'calculateDivineInfluenceCost'], [JwtAuthMiddleware::class]);
    $router->post($api . '/influence/divine/apply', [InfluenceController::class, 'applyDivineInfluence'], [JwtAuthMiddleware::class]);
    $router->post($api . '/influence/hero/empower', [InfluenceController::class, 'empowerHero'], [JwtAuthMiddleware::class]);
    $router->post($api . '/influence/hero/guide', [InfluenceController::class, 'guideHero'], [JwtAuthMiddleware::class]);
    $router->post($api . '/influence/region/guide-research', [InfluenceController::class, 'guideRegionResearch'], [JwtAuthMiddleware::class]);

    // Status
    $router->get($api . '/status', [StatusController::class, 'getGameStatus'], [JwtAuthMiddleware::class]);

    // Statistics Routes
    $router->get($api . '/statistics/summary', [StatisticsController::class, 'getSummary'], [JwtAuthMiddleware::class]);
    $router->get($api . '/statistics/heroes', [StatisticsController::class, 'getHeroStats'], [JwtAuthMiddleware::class]);
    $router->get($api . '/statistics/regions', [StatisticsController::class, 'getRegionStats'], [JwtAuthMiddleware::class]);
    $router->get($api . '/statistics/financials', [StatisticsController::class, 'getFinancialStats'], [JwtAuthMiddleware::class]);

    // Admin Routes
    $router->post($api . '/admin/process-expired-bets', [BettingController::class, 'processExpiredBets'], [AdminAuthMiddleware::class, JwtAuthMiddleware::class]);
};
