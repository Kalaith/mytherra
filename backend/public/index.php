<?php

$centralAutoload = __DIR__ . '/../../../vendor/autoload.php';
if (!file_exists($centralAutoload)) {
    throw new \RuntimeException('Central vendor autoload not found at ' . $centralAutoload);
}
$loader = require $centralAutoload;
//$loader->addPsr4('App\\', __DIR__ . '/../src/', true); // Disabled to prevent conflicts

// Local autoloader MUST be registered AFTER composer to ensure it prepends successfully
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) {
        require $file;
    }
}, true, true);

use Dotenv\Dotenv;
use App\External\DatabaseService;
use App\Utils\ContainerConfig;
use App\Core\Router;

// Load environment variables first
$dotenvPath = __DIR__ . '/..';
if (!file_exists($dotenvPath . '/.env')) {
    throw new \RuntimeException('Missing .env at ' . $dotenvPath . '/.env');
}
$dotenv = Dotenv::createImmutable($dotenvPath);
$dotenv->load();

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

// Router (Custom, removing Slim App)
$router = new Router($container);

// Set base path for subdirectory deployment
if (isset($_ENV['APP_BASE_PATH']) && $_ENV['APP_BASE_PATH']) {
    $router->setBasePath(rtrim($_ENV['APP_BASE_PATH'], '/'));
} else {
    // Auto-detect base path
    $requestPath = $_SERVER['REQUEST_URI'] ?? '';
    $requestPath = parse_url($requestPath, PHP_URL_PATH) ?? '';
    
    // Check key segments
    $segments = ['/mytherra'];
    foreach ($segments as $segment) {
        if (strpos($requestPath, $segment) === 0) {
            $router->setBasePath($segment);
            break;
        }
    }
}

// Handle CORS preflight
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Origin, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    http_response_code(200);
    exit;
}

// Load routes
(require __DIR__ . '/../src/routes/router.php')($router);

// Run router
$router->handle();
