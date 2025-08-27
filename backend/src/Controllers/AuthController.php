<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\AuthPortalService;
use App\Models\User;

class AuthController
{
    private AuthPortalService $authPortalService;

    public function __construct(AuthPortalService $authPortalService)
    {
        $this->authPortalService = $authPortalService;
    }

    /**
     * Handle callback from auth portal with JWT token
     */
    public function callback(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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

        // Validate token and get user data
        $authUser = $this->authPortalService->getUserFromToken($token);
        
        if (!$authUser) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Invalid token'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        // Create or update local user
        try {
            $localUser = $this->authPortalService->createOrUpdateLocalUser($authUser);
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Authentication successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $localUser->id,
                        'auth_user_id' => $localUser->auth_user_id,
                        'display_name' => $localUser->display_name,
                        'divine_influence' => $localUser->divine_influence,
                        'divine_favor' => $localUser->divine_favor,
                        'role' => $authUser['role'] ?? 'user'
                    ]
                ]
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            error_log('Auth callback error: ' . $e->getMessage());
            
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Failed to create user profile'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    /**
     * Get current authenticated user information
     */
    public function getCurrentUser(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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
                    'betting_stats' => $localUser->betting_stats,
                    'game_preferences' => $localUser->game_preferences,
                    'role' => $authUser['role'] ?? 'user',
                    'is_active' => $localUser->is_active
                ]
            ]
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get login redirect URL
     */
    public function getLoginUrl(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $returnUrl = $queryParams['return_url'] ?? null;

        $loginUrl = $this->authPortalService->getLoginRedirectUrl($returnUrl);

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
    public function getRegisterUrl(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $returnUrl = $queryParams['return_url'] ?? null;

        $registerUrl = $this->authPortalService->getRegisterRedirectUrl($returnUrl);

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
    public function updatePreferences(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $localUser = $request->getAttribute('user');
        
        if (!$localUser) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'User not found'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $body = json_decode($request->getBody()->getContents(), true);
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
     * Logout (invalidates token on frontend, no server-side action needed for JWT)
     */
    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Logged out successfully'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
