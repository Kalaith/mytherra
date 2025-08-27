<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Services\AuthPortalService;

class AdminAuthMiddleware implements MiddlewareInterface
{
    private AuthPortalService $authPortalService;

    public function __construct(AuthPortalService $authPortalService)
    {
        $this->authPortalService = $authPortalService;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // This middleware should run after JwtAuthMiddleware
        $authUser = $request->getAttribute('auth_user');
        
        if (!$authUser) {
            return $this->forbiddenResponse('Authentication required');
        }

        // Check if user has admin role
        if (!$this->authPortalService->isAdmin($authUser)) {
            return $this->forbiddenResponse('Admin access required');
        }

        return $handler->handle($request);
    }

    private function forbiddenResponse(string $message = 'Forbidden'): ResponseInterface
    {
        $response = new \Nyholm\Psr7\Response();
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message,
            'error_code' => 'FORBIDDEN'
        ]));
        
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(403);
    }
}
