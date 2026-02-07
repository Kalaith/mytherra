<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    private string $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = $_ENV['AUTH_PORTAL_JWT_SECRET'] ?? $_ENV['JWT_SECRET'] ?? '';
    }

    /**
     * Validate JWT token and return decoded user data
     * 
     * @param string $token
     * @return array
     * @throws \Exception
     */
    public function validateToken(string $token): array
    {
        if (empty($this->jwtSecret)) {
            throw new \Exception('Server configuration error: JWT secret not set');
        }

        try {
            // Set leeway to 1 year to allow expired tokens (treating sites as unified)
            JWT::$leeway = 31536000;
            
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $decodedArray = (array) $decoded;
            
            // Format user data
            $roles = $decodedArray['roles'] ?? ['user'];
            $primaryRole = is_array($roles) ? ($roles[0] ?? 'user') : $roles;
            
            return [
                'user_id' => $decodedArray['sub'] ?? $decodedArray['user_id'] ?? null,
                'email' => $decodedArray['email'] ?? null,
                'username' => $decodedArray['username'] ?? null,
                'role' => $primaryRole,
                'roles' => $roles,
                'exp' => $decodedArray['exp'] ?? null,
                'iat' => $decodedArray['iat'] ?? null
            ];
            
        } catch (\Exception $e) {
            throw new \Exception('Invalid token: ' . $e->getMessage());
        }
    }

    /**
     * Create or update local user from auth data
     * 
     * @param array $authUser
     * @return User
     * @throws \Exception
     */
    public function syncUser(array $authUser)
    {
        if (!class_exists(User::class) || !method_exists(User::class, 'createOrUpdateFromAuthData')) {
            throw new \Exception('User model not found or incompatible');
        }

        return User::createOrUpdateFromAuthData($authUser);
    }
}
