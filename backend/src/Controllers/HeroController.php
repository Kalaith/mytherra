<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Actions\HeroActions;
use App\Traits\ApiResponseTrait;

class HeroController
{
    use ApiResponseTrait;

    public function __construct(
        private HeroActions $heroActions
    ) {}

    /**
     * Get all heroes
     */
    public function getAllHeroes(Request $request, Response $response): Response
    {
        return $this->handleApiAction(
            $response,
            fn() => $this->heroActions->fetchAllHeroes([]),
            'fetching heroes',
            'Hero not found'
        );
    }

    /**
     * Get hero by ID
     */    
    public function getHeroById(Request $request, Response $response, array $args): Response
    {
        return $this->handleApiAction(
            $response,
            fn() => $this->heroActions->fetchHeroById($args['id']),
            'fetching hero',
            'Hero not found'
        );
    }
}
