<?php
/**
 * Hero Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Hero;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;
use Dotenv\Dotenv;

class HeroEndpointTest extends TestCase
{    
    private $app;    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';

        $this->app = $app;
    }

    public function testGetHeroById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/heroes/hero-001')
            ->withHeader('Accept', 'application/json');

        // Handle the request
        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        // Assert response status code
        $this->assertEquals(200, $response->getStatusCode());

        // Assert response structure
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertTrue($data['success']);

        // Assert hero data
        $hero = $data['data'];
        $this->assertEquals('hero-001', $hero['id']);
        $this->assertEquals('Eldara the Wise', $hero['name']);
        $this->assertEquals('scholar', $hero['role']);
        $this->assertEquals('region-001', $hero['region_id']);
        $this->assertEquals(3, $hero['level']);
        $this->assertTrue($hero['is_alive']);
        $this->assertEquals(45, $hero['age']);

        // Assert arrays are properly decoded
        $this->assertIsArray($hero['feats']);
        $this->assertContains('Discovered ancient runes', $hero['feats']);
        $this->assertContains('Founded the Academy of Magic', $hero['feats']);

        $this->assertIsArray($hero['personality_traits']);
        $this->assertContains('curious', $hero['personality_traits']);
        $this->assertContains('patient', $hero['personality_traits']);
        $this->assertContains('analytical', $hero['personality_traits']);

        // Assert alignment structure
        $this->assertIsArray($hero['alignment']);
        $this->assertEquals(70, $hero['alignment']['good']);
        $this->assertEquals(30, $hero['alignment']['chaotic']);

        // Assert derived data
        $this->assertEquals('Lawful Good', $hero['alignmentDescription']);
    }

    public function testGetNonExistentHero(): void
    {
        // Create test request for non-existent hero
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/heroes/non-existent-hero')
            ->withHeader('Accept', 'application/json');

        // Handle the request
        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);        // Assert response status code (always 200 for consistent frontend handling)
        $this->assertEquals(200, $response->getStatusCode());

        // Assert response structure
        $this->assertArrayHasKey('success', $data);
        $this->assertFalse($data['success']);
        
        // Assert error message
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Hero not found', $data['message']);
    }
}