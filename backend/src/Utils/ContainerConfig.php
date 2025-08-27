<?php

namespace App\Utils;

use DI\ContainerBuilder;
use App\External\DatabaseService;

// Controllers
use App\Controllers\StatusController;
use App\Controllers\RegionController;
use App\Controllers\HeroController;
use App\Controllers\EventController;
use App\Controllers\SettlementController;
use App\Controllers\BuildingController;
use App\Controllers\LandmarkController;
use App\Controllers\ResourceNodeController;
use App\Controllers\BettingController;
use App\Controllers\InfluenceController;
use App\Controllers\AuthController;

// Actions
use App\Actions\RegionActions;
use App\Actions\HeroActions;
use App\Actions\EventActions;
use App\Actions\SettlementActions;
use App\Actions\BuildingActions;
use App\Actions\LandmarkActions;
use App\Actions\StatusActions;
use App\Actions\BettingActions;
use App\Actions\InfluenceActions;
use App\Actions\ResourceNodeActions;

// Repositories
use App\External\RegionRepository;
use App\External\HeroRepository;
use App\External\EventRepository;
use App\External\SettlementRepository;
use App\External\BuildingRepository;
use App\External\LandmarkRepository;
use App\External\StatusRepository;
use App\External\BettingRepository;
use App\External\InfluenceRepository;
use App\External\OddsRepository;
use App\External\BettingConfigRepository;
use App\External\ResourceNodeRepository;

// Services
use App\Services\GameConfigService;
use App\Services\DivineInfluenceService;
use App\Services\OddsCalculationService;
use App\Services\DivineBettingService;
use App\Services\AuthPortalService;

// Middleware
use App\Middleware\JwtAuthMiddleware;
use App\Middleware\AdminAuthMiddleware;

class ContainerConfig
{
    public static function createContainer()
    {        $containerBuilder = new ContainerBuilder();
        
        // Enable compilation for better performance in production
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
            $containerBuilder->enableCompilation(__DIR__ . '/../../var/cache');
        }

        $containerBuilder->addDefinitions([
            // Database Service (Singleton)
            DatabaseService::class => function () {
                return DatabaseService::getInstance();
            },            // ====================================
            // REPOSITORIES
            // ====================================
            RegionRepository::class => function($container) {
                return new RegionRepository();
            },            HeroRepository::class => function($container) {
                return new HeroRepository();
            },            EventRepository::class => function($container) {
                return new EventRepository();
            },
            SettlementRepository::class => function($container) {
                return new SettlementRepository($container->get(DatabaseService::class));
            },
            BuildingRepository::class => function($container) {
                return new BuildingRepository($container->get(DatabaseService::class));
            },
            LandmarkRepository::class => function($container) {
                return new LandmarkRepository($container->get(DatabaseService::class));
            },
            StatusRepository::class => function($container) {
                return new StatusRepository($container->get(DatabaseService::class));
            },
            BettingRepository::class => function($container) {
                return new BettingRepository($container->get(DatabaseService::class));
            },
            InfluenceRepository::class => function($container) {
                return new InfluenceRepository($container->get(DatabaseService::class));
            },
            OddsRepository::class => function($container) {
                return new OddsRepository($container->get(DatabaseService::class));
            },            BettingConfigRepository::class => function($container) {
                return new BettingConfigRepository($container->get(DatabaseService::class));
            },
            ResourceNodeRepository::class => function($container) {
                return new ResourceNodeRepository($container->get(DatabaseService::class));
            },

            // ====================================
            // SERVICES
            // ====================================
            GameConfigService::class => function() {
                return GameConfigService::getInstance();
            },            DivineInfluenceService::class => function($container) {
                return new DivineInfluenceService();
            },
            OddsCalculationService::class => function($container) {
                return new OddsCalculationService(
                    $container->get(OddsRepository::class),
                    $container->get(BettingConfigRepository::class)
                );
            },            DivineBettingService::class => function($container) {
                return new DivineBettingService(
                    $container->get(BettingRepository::class),
                    $container->get(SettlementRepository::class),
                    $container->get(LandmarkRepository::class),
                    $container->get(HeroRepository::class),
                    $container->get(OddsCalculationService::class)
                );
            },

            AuthPortalService::class => function($container) {
                return new AuthPortalService();
            },

            // ====================================
            // MIDDLEWARE
            // ====================================
            JwtAuthMiddleware::class => function($container) {
                return new JwtAuthMiddleware($container->get(AuthPortalService::class));
            },

            AdminAuthMiddleware::class => function($container) {
                return new AdminAuthMiddleware($container->get(AuthPortalService::class));
            },

            // ====================================
            // ACTIONS
            // ====================================
            RegionActions::class => function($container) {
                return new RegionActions($container->get(RegionRepository::class));
            },
            HeroActions::class => function($container) {
                return new HeroActions($container->get(HeroRepository::class));
            },
            EventActions::class => function($container) {
                return new EventActions($container->get(EventRepository::class));
            },
            SettlementActions::class => function($container) {
                return new SettlementActions($container->get(SettlementRepository::class));
            },
            BuildingActions::class => function($container) {
                return new BuildingActions($container->get(BuildingRepository::class));
            },            LandmarkActions::class => function($container) {
                return new LandmarkActions($container->get(LandmarkRepository::class));
            },
            ResourceNodeActions::class => function($container) {
                return new ResourceNodeActions($container->get(ResourceNodeRepository::class));
            },
            StatusActions::class => function($container) {
                return new StatusActions($container->get(GameConfigService::class));
            },            BettingActions::class => function($container) {
                return new BettingActions(
                    $container->get(BettingRepository::class),
                    $container->get(OddsCalculationService::class),
                    $container->get(DivineBettingService::class)
                );
            },
            InfluenceActions::class => function($container) {
                return new InfluenceActions(
                    $container->get(DivineInfluenceService::class),
                    $container->get(InfluenceRepository::class)
                );
            },

            // ====================================
            // CONTROLLERS
            // ====================================
            StatusController::class => function($container) {
                return new StatusController(
                    $container->get(StatusActions::class)
                );
            },
            RegionController::class => function($container) {
                return new RegionController(
                    $container->get(RegionActions::class)
                );
            },
            HeroController::class => function($container) {
                return new HeroController(
                    $container->get(HeroActions::class)
                );
            },
            EventController::class => function($container) {
                return new EventController(
                    $container->get(EventActions::class)
                );
            },
            SettlementController::class => function($container) {
                return new SettlementController(
                    $container->get(SettlementActions::class),
                    $container->get(BuildingActions::class)
                );
            },
            BuildingController::class => function($container) {
                return new BuildingController(
                    $container->get(BuildingActions::class)
                );
            },
            LandmarkController::class => function($container) {
                return new LandmarkController(
                    $container->get(LandmarkActions::class)
                );
            },
            BettingController::class => function($container) {
                return new BettingController(
                    $container->get(BettingActions::class)
                );
            },
            InfluenceController::class => function($container) {
                return new InfluenceController(
                    $container->get(InfluenceActions::class)
                );
            },

            AuthController::class => function($container) {
                return new AuthController(
                    $container->get(AuthPortalService::class)
                );
            }
        ]);

        return $containerBuilder->build();
    }
}
