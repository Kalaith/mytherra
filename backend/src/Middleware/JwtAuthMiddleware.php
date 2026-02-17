<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtAuthMiddleware
{
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Request|Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        // Get the Authorization header
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            return $this->unauthorizedResponse($response, 'Missing Authorization header');
        }

        // Extract the token from "Bearer <token>"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->unauthorizedResponse($response, 'Invalid Authorization header format');
        }

        $token = $matches[1];
        
        // Validate token
        $secret = $_ENV['JWT_SECRET'] ?? '';
        if (empty($secret)) {
             return $this->unauthorizedResponse($response, 'Server configuration error');
        }

        // Set leeway to 1 year to allow expired tokens (referencing Web Hatchery session trust)
        JWT::$leeway = 31536000;

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            $decodedArray = (array) $decoded;
            
            // Format user data
            $roles = $decodedArray['roles'] ?? ['user'];
            $primaryRole = is_array($roles) ? ($roles[0] ?? 'user') : $roles;
            
            $authUser = [
                'user_id' => $decodedArray['sub'] ?? $decodedArray['user_id'] ?? null,
                'email' => $decodedArray['email'] ?? null,
                'username' => $decodedArray['username'] ?? null,
                'role' => $primaryRole,
                'roles' => $roles,
                'exp' => $decodedArray['exp'] ?? null,
                'iat' => $decodedArray['iat'] ?? null
            ];

            error_log('JwtAuthMiddleware: token decoded for user_id=' . ($authUser['user_id'] ?? 'null'));
            
        } catch (\Exception $e) {
            error_log('JwtAuthMiddleware: token decode failed - ' . $e->getMessage());
            return $this->unauthorizedResponse($response, 'Invalid token');
        }
        
        if (!$authUser || !$authUser['user_id']) {
            return $this->unauthorizedResponse($response, 'Invalid token user data');
        }

        // Create or update the local user
        try {
            // Check if User class exists and method exists to avoid crash if model is missing
            if (class_exists(User::class) && method_exists(User::class, 'createOrUpdateFromAuthData')) {
                $localUser = User::createOrUpdateFromAuthData($authUser);
                error_log('JwtAuthMiddleware: local user synced id=' . ($localUser->id ?? 'null') . ' auth_user_id=' . ($localUser->auth_user_id ?? 'null'));
            } else {
                 // Fallback if model isn't ready, mostly for testing
                 // But we really need the user. 
                 // Assuming logic is consistent, we throw if fails.
                 throw new \Exception('User model not found');
            }
            
            // Add both auth user data and local user to the request attributes
            return $request
                ->withAttribute('auth_user', $authUser)
                ->withAttribute('user', $localUser);
                
        } catch (\Exception $e) {
            error_log('JwtAuthMiddleware: local user sync failed - ' . $e->getMessage());
            return $this->unauthorizedResponse($response, 'User creation failed: ' . $e->getMessage());
        }
    }

    private function unauthorizedResponse(Response $response, string $message = 'Unauthorized'): Response
    {
        $portalBaseUrl = rtrim((string) ($_ENV['AUTH_PORTAL_BASE_URL'] ?? ''), '/');
        $loginUrl = $_ENV['WEB_HATCHERY_LOGIN_URL'] ?? ($portalBaseUrl !== '' ? $portalBaseUrl . '/login' : '');

        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message,
            'error_code' => 'UNAUTHORIZED',
            'login_url' => $loginUrl
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }
}
