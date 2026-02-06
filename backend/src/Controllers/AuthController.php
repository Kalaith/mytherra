<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{
    private string $authPortalBaseUrl;
    private $authService;

    public function __construct()
    {
        $this->authPortalBaseUrl = $_ENV['AUTH_PORTAL_BASE_URL'] ?? 'http://localhost:8000';
        // In a real DI container this would be injected, but for now we instantiate it
        $this->authService = new \App\Services\AuthService();
    }

    /**
     * Handle callback from auth portal with JWT token
     */
    public function callback(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $token = $queryParams['token'] ?? null;

        if (!$token) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'No token provided'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            // Validate token
            $authUser = $this->authService->validateToken($token);

            // Create or update local user
            $localUser = $this->authService->syncUser($authUser);
            
            // Assuming User model has these properties public or via getters
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Authentication successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $localUser->id,
                        'auth_user_id' => $localUser->auth_user_id,
                        'display_name' => $localUser->display_name ?? $authUser['username'],
                        'divine_influence' => $localUser->divine_influence ?? 0,
                        'divine_favor' => $localUser->divine_favor ?? 0,
                        'role' => $authUser['role'] ?? 'user'
                    ]
                ]
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            error_log('Auth callback error: ' . $e->getMessage());
            
            $status = 500;
            $message = 'Authentication failed';

            if (strpos($e->getMessage(), 'Invalid token') !== false) {
                $status = 401;
                $message = 'Invalid token';
            } elseif (strpos($e->getMessage(), 'Server configuration') !== false) {
                $message = 'Server configuration error';
            }

            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $message
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
        }
    }

    /**
     * Get current authenticated user information
     */
    public function getCurrentUser(Request $request, Response $response): Response
    {
        $authUser = $request->getAttribute('auth_user');
        $localUser = $request->getAttribute('user');

        if (!$authUser || !$localUser) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'User not authenticated'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $localUser->id,
                    'auth_user_id' => $localUser->auth_user_id,
                    'email' => $localUser->auth_email,
                    'username' => $localUser->auth_username,
                    'display_name' => $localUser->display_name,
                    'divine_influence' => $localUser->divine_influence,
                    'divine_favor' => $localUser->divine_favor,
                    'betting_stats' => $localUser->betting_stats ?? [],
                    'game_preferences' => $localUser->game_preferences ?? [],
                    'role' => $authUser['role'] ?? 'user',
                    'is_active' => $localUser->is_active ?? true
                ]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get login redirect URL
     */
    public function getLoginUrl(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $returnUrl = $queryParams['return_url'] ?? null;

        $params = [];
        if ($returnUrl) {
            $params['redirect'] = urlencode($returnUrl);
        }
        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        $loginUrl = $this->authPortalBaseUrl . '/login' . $queryString;

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'login_url' => $loginUrl
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get register redirect URL
     */
    public function getRegisterUrl(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        $returnUrl = $queryParams['return_url'] ?? null;

        $params = [];
        if ($returnUrl) {
            $params['redirect'] = urlencode($returnUrl);
        }
        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        $registerUrl = $this->authPortalBaseUrl . '/register' . $queryString;

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => [
                'register_url' => $registerUrl
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Update user game preferences
     */
    public function updatePreferences(Request $request, Response $response): Response
    {
        $localUser = $request->getAttribute('user');
        
        if (!$localUser) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'User not found'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $body = json_decode((string)$request->getBody(), true);
        $preferences = $body['preferences'] ?? [];

        if (!is_array($preferences)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Invalid preferences format'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $localUser->updateGamePreferences($preferences);
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Preferences updated successfully',
                'data' => [
                    'preferences' => $localUser->game_preferences
                ]
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            error_log('Failed to update preferences: ' . $e->getMessage());
            
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Failed to update preferences'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Logged out successfully'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
