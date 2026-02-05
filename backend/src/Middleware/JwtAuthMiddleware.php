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
        $secret = $_ENV['AUTH_PORTAL_JWT_SECRET'] ?? $_ENV['JWT_SECRET'] ?? '';
        if (empty($secret)) {
             return $this->unauthorizedResponse($response, 'Server configuration error');
        }

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
            
        } catch (\Exception $e) {
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
            return $this->unauthorizedResponse($response, 'User creation failed: ' . $e->getMessage());
        }
    }

    private function unauthorizedResponse(Response $response, string $message = 'Unauthorized'): Response
    {
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message,
            'error_code' => 'UNAUTHORIZED'
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }
}
