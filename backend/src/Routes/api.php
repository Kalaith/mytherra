<?php

use Slim\Routing\RouteCollectorProxy;
use App\External\DatabaseService;
use App\Controllers\RegionController;
use App\Controllers\HeroController;
use App\Controllers\EventController;
use App\Controllers\InfluenceController;
use App\Controllers\StatusController;
use App\Controllers\SettlementController;
use App\Controllers\BuildingController;
use App\Controllers\LandmarkController;
use App\Controllers\BettingController;
use App\Controllers\AuthController;
use App\Controllers\Auth0Controller;
use App\Middleware\JwtAuthMiddleware;
use App\Middleware\AdminAuthMiddleware;
use App\Middleware\Auth0Middleware;

// API Routes
$app->group('/api', function (RouteCollectorProxy $group) {
    // API status endpoint (public)
    $group->get('/site-status', function($request, $response) {
        $response->getBody()->write(json_encode(['status' => 'OK']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ==================================================
    // Authentication Routes (Public)
    // ==================================================
    $group->get('/auth/login-url', [AuthController::class, 'getLoginUrl']);
    $group->get('/auth/register-url', [AuthController::class, 'getRegisterUrl']);
    $group->get('/auth/callback', [AuthController::class, 'callback']);

    // Auth0 endpoints
    $group->group('/auth', function (RouteCollectorProxy $auth) {
        $auth->post('/verify-user', [Auth0Controller::class, 'verifyUser'])->add(new Auth0Middleware());
        $auth->get('/current-user', [Auth0Controller::class, 'getCurrentUser'])->add(new Auth0Middleware());
        $auth->get('/validate-session', [Auth0Controller::class, 'validateSession'])->add(new Auth0Middleware());
    });

    // ==================================================
    // Protected Routes (Require Authentication)
    // ==================================================
    $group->group('', function (RouteCollectorProxy $authGroup) {
        
        // Authentication Routes (Protected)
        $authGroup->get('/auth/me', [AuthController::class, 'getCurrentUser']);
        $authGroup->post('/auth/logout', [AuthController::class, 'logout']);
        $authGroup->put('/auth/preferences', [AuthController::class, 'updatePreferences']);

        // ==================================================
        // Region Routes
        // ==================================================
        $authGroup->get('/regions', [RegionController::class, 'getAllRegions']);
        $authGroup->get('/regions/{id}', [RegionController::class, 'getRegionById']);
        $authGroup->get('/regions/{id}/landmarks', [RegionController::class, 'getRegionLandmarks']);
        $authGroup->post('/regions', [RegionController::class, 'createRegion']);
        $authGroup->post('/regions/{id}/process', [RegionController::class, 'processRegionTick']);

        // ==================================================
        // Hero Routes
        // ==================================================
        $authGroup->get('/heroes', [HeroController::class, 'getAllHeroes']);
        $authGroup->get('/heroes/{id}', [HeroController::class, 'getHeroById']);

        // ==================================================
        // Settlement Routes
        // ==================================================
        $authGroup->get('/settlements', [SettlementController::class, 'getAllSettlements']);
        $authGroup->get('/settlements/{id}', [SettlementController::class, 'getSettlementById']);
        $authGroup->get('/settlements/{id}/buildings', [SettlementController::class, 'getSettlementBuildings']);

        // ==================================================
        // Event Routes
        // ==================================================
        $authGroup->get('/events', [EventController::class, 'getAllEvents']);
        $authGroup->get('/events/{id}', [EventController::class, 'getEventById']);

        // ==================================================
        // Building Routes
        // ==================================================
        $authGroup->get('/buildings', [BuildingController::class, 'getAllBuildings']);
        $authGroup->get('/buildings/{id}', [BuildingController::class, 'getBuildingById']);
        $authGroup->post('/buildings', [BuildingController::class, 'createBuilding']);
        $authGroup->put('/buildings/{id}', [BuildingController::class, 'updateBuilding']);
        $authGroup->delete('/buildings/{id}', [BuildingController::class, 'deleteBuilding']);
        
        // ==================================================
        // Landmark Routes
        // ==================================================
        $authGroup->get('/landmarks', [LandmarkController::class, 'getAllLandmarks']);
        $authGroup->get('/landmarks/{id}', [LandmarkController::class, 'getLandmarkById']);
        $authGroup->post('/landmarks', [LandmarkController::class, 'createLandmark']);
        $authGroup->put('/landmarks/{id}', [LandmarkController::class, 'updateLandmark']);
        $authGroup->delete('/landmarks/{id}', [LandmarkController::class, 'deleteLandmark']);
        $authGroup->post('/landmarks/{id}/discover', [LandmarkController::class, 'discoverLandmark']);
      
        
        // ==================================================
        // Betting Routes
        // =========================h=========================
        $authGroup->post('/bets', [BettingController::class, 'placeDivineBet']);
        $authGroup->get('/bets', [BettingController::class, 'getAllDivineBets']);
        $authGroup->get('/bets/{id}', [BettingController::class, 'getDivineBetById']);
        $authGroup->get('/speculation-events', [BettingController::class, 'getSpeculationEvents']);
        $authGroup->get('/betting-odds', [BettingController::class, 'getBettingOdds']);
        
        // ==================================================
        // Divine Influence Routes
        // ==================================================
        $authGroup->post('/influence/divine/calculate-cost', [InfluenceController::class, 'calculateDivineInfluenceCost']);
        $authGroup->post('/influence/divine/apply', [InfluenceController::class, 'applyDivineInfluence']);
        
        // ==================================================
        // Hero Influence Routes
        // ==================================================
        $authGroup->post('/influence/hero/empower', [InfluenceController::class, 'empowerHero']);
        $authGroup->post('/influence/hero/guide', [InfluenceController::class, 'guideHero']);
        
        // ==================================================
        // Region Influence Routes
        // ==================================================
        $authGroup->post('/influence/region/guide-research', [InfluenceController::class, 'guideRegionResearch']);

        // ==================================================
        // Game Status Route
        // ==================================================
        $authGroup->get('/status', [StatusController::class, 'getGameStatus']);
        
    })->add(JwtAuthMiddleware::class);

    // ==================================================
    // Admin Routes (Require Admin Role)
    // ==================================================
    $group->group('/admin', function (RouteCollectorProxy $adminGroup) {
        $adminGroup->post('/process-expired-bets', [BettingController::class, 'processExpiredBets']);
        // Add more admin-only routes here as needed
    })->add(AdminAuthMiddleware::class)->add(JwtAuthMiddleware::class);
});

// Catch-all route for unmatched API routes only
$app->any('/api/{routes:.*}', function ($request, $response) {
    $errorResponse = [
        'success' => false,
        'message' => 'Route not found'
    ];
    
    $response->getBody()->write(json_encode($errorResponse));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});
