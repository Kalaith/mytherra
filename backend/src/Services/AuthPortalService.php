<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class AuthPortalService
{
    private string $authPortalBaseUrl;
    private string $authPortalApiUrl;
    private string $jwtSecret;

    public function __construct()
    {
        $this->authPortalBaseUrl = $_ENV['AUTH_PORTAL_BASE_URL'] ?? 'http://localhost:8000';
        $this->authPortalApiUrl = $_ENV['AUTH_PORTAL_API_URL'] ?? 'http://localhost:8000/api';
        $this->jwtSecret = $_ENV['AUTH_PORTAL_JWT_SECRET'] ?? 'default_secret';
    }

    /**
     * Validate a JWT token from the auth portal
     */
    public function validateToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            $logMsg = date('[Y-m-d H:i:s] ') . "Error: " . $e->getMessage() . "\n";
            file_put_contents(__DIR__ . '/../../jwt_debug.log', $logMsg, FILE_APPEND);
            error_log('JWT validation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract user data from a validated JWT token
     */
    public function getUserFromToken(string $token): ?array
    {
        $decoded = $this->validateToken($token);
        
        if (!$decoded) {
            return null;
        }

        // Extract user information from the token payload
        // Auth portal uses 'sub' for user ID and 'roles' as array
        $roles = $decoded['roles'] ?? ['user'];
        $primaryRole = is_array($roles) ? ($roles[0] ?? 'user') : $roles;

        return [
            'user_id' => $decoded['sub'] ?? $decoded['user_id'] ?? null,
            'email' => $decoded['email'] ?? null,
            'username' => $decoded['username'] ?? null, // May not be in token
            'role' => $primaryRole,
            'roles' => $roles,
            'exp' => $decoded['exp'] ?? null,
            'iat' => $decoded['iat'] ?? null
        ];
    }

    /**
     * Create or update a local Mytherra user from auth portal data
     */
    public function createOrUpdateLocalUser(array $authUser): User
    {
        return User::createOrUpdateFromAuthData($authUser);
    }

    /**
     * Get user details from auth portal API (if needed for additional data)
     */
    public function getUserFromAuthPortal(int $userId): ?array
    {
        try {
            // Use the /me endpoint which is more reliable than user-specific endpoint
            $url = $this->authPortalApiUrl . '/me';
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'Content-Type: application/json',
                        'User-Agent: Mytherra/1.0'
                    ],
                    'timeout' => 10
                ]
            ]);

            $response = file_get_contents($url, false, $context);
            
            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);
            
            // Handle the response structure from auth portal
            if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                return $data['data'];
            }
            
            return null;
            
        } catch (\Exception $e) {
            error_log('Failed to fetch user from auth portal: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Link an existing Mytherra user to an auth portal account
     */
    public function linkExistingUser(int $mytherraUserId, int $authUserId): bool
    {
        try {
            $user = User::find($mytherraUserId);
            if (!$user) {
                return false;
            }

            // Get auth portal user data
            $authData = $this->getUserFromAuthPortal($authUserId);
            if (!$authData) {
                return false;
            }

            // Update the user with auth portal information
            $user->auth_user_id = $authUserId;
            $user->auth_email = $authData['email'];
            $user->auth_username = $authData['username'];
            
            return $user->save();
            
        } catch (\Exception $e) {
            error_log('Failed to link user accounts: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate redirect URL to auth portal
     */
    public function getLoginRedirectUrl(string $returnUrl = null): string
    {
        $params = [];
        if ($returnUrl) {
            $params['redirect'] = urlencode($returnUrl);
        }
        
        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        
        return $this->authPortalBaseUrl . '/login' . $queryString;
    }

    /**
     * Generate redirect URL to auth portal registration
     */
    public function getRegisterRedirectUrl(string $returnUrl = null): string
    {
        $params = [];
        if ($returnUrl) {
            $params['redirect'] = urlencode($returnUrl);
        }
        
        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        
        return $this->authPortalBaseUrl . '/register' . $queryString;
    }

    /**
     * Check if a user has a specific role
     */
    public function userHasRole(array $authUser, string $role): bool
    {
        $userRole = $authUser['role'] ?? 'user';
        
        // Admin has access to everything
        if ($userRole === 'admin') {
            return true;
        }
        
        return $userRole === $role;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(array $authUser): bool
    {
        return $this->userHasRole($authUser, 'admin');
    }
}
