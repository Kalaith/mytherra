<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use App\External\DatabaseService;
use App\Utils\ContainerConfig;

// Determine if we're in test mode
$isTestMode = defined('TEST_MODE') && TEST_MODE === true;

// Load environment variables first
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Add required environment variables
$required_env_vars = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];
foreach ($required_env_vars as $var) {
    if (!isset($_ENV[$var])) {
        throw new \RuntimeException("Missing required environment variable: {$var}");
    }
}

// Create DI Container
$container = ContainerConfig::createContainer();

// Initialize database service after environment variables are loaded
$db = DatabaseService::getInstance();

// Create app with DI container
AppFactory::setContainer($container);
global $app;
$app = AppFactory::create();

// Set base path for subdirectory deployment (preview environment)
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'preview') {
    $app->setBasePath('/mytherra');
}

// Add middleware
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Custom error handling
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

// Set custom error renderer
$errorHandler->setDefaultErrorRenderer('application/json', function ($exception, $displayErrorDetails) {
    error_log("[ERROR] Uncaught exception: " . $exception->getMessage());
    $responseData = [
        'success' => false,
        'message' => $displayErrorDetails ? $exception->getMessage() : 'An internal error occurred'
    ];
    return json_encode($responseData);
});

// CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withStatus($request->getMethod() === 'OPTIONS' ? 200 : $response->getStatusCode());
});

// Import and configure routes
require __DIR__ . '/../src/Routes/api.php';

// Run the application
$app->run();

// Return app instance for testing
return $app;
