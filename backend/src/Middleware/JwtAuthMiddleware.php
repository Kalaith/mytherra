<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Services\AuthPortalService;
use App\Models\User;

class JwtAuthMiddleware implements MiddlewareInterface
{
    private AuthPortalService $authPortalService;

    public function __construct(AuthPortalService $authPortalService)
    {
        $this->authPortalService = $authPortalService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Get the Authorization header
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            return $this->unauthorizedResponse();
        }

        // Extract the token from "Bearer <token>"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->unauthorizedResponse();
        }

        $token = $matches[1];

        // Validate the token with auth portal service
        $authUser = $this->authPortalService->getUserFromToken($token);
        
        if (!$authUser || !$authUser['user_id']) {
            return $this->unauthorizedResponse();
        }

        // Check token expiration
        if (isset($authUser['exp']) && $authUser['exp'] < time()) {
            return $this->unauthorizedResponse('Token expired');
        }

        // Create or update the local user
        try {
            $localUser = $this->authPortalService->createOrUpdateLocalUser($authUser);
            
            // Add both auth user data and local user to the request attributes
            $request = $request
                ->withAttribute('auth_user', $authUser)
                ->withAttribute('user', $localUser);
                
        } catch (\Exception $e) {
            error_log('Failed to create/update local user: ' . $e->getMessage());
            return $this->unauthorizedResponse('User creation failed');
        }

        return $handler->handle($request);
    }

    private function unauthorizedResponse(string $message = 'Unauthorized'): ResponseInterface
    {
        $response = new \Nyholm\Psr7\Response();
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
