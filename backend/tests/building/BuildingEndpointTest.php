<?php
/**
 * Building Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Building;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class BuildingEndpointTest extends TestCase
{    
    private $app;
    
    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';
        $this->app = $app;
    }

    public function testGetBuildingById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/buildings/building-001')
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

        // Assert building data
        $building = $data['data'];
        $this->assertEquals('building-001', $building['id']);
        $this->assertArrayHasKey('name', $building);
        $this->assertArrayHasKey('type', $building);
        $this->assertArrayHasKey('settlement_id', $building);
        $this->assertArrayHasKey('level', $building);
        $this->assertArrayHasKey('status', $building);
    }

    public function testGetNonExistentBuilding(): void
    {
        // Create test request for non-existent building
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/buildings/non-existent-building')
            ->withHeader('Accept', 'application/json');

        // Handle the request
        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        // Assert response status code (always 200 for consistent frontend handling)
        $this->assertEquals(200, $response->getStatusCode());

        // Assert response structure
        $this->assertArrayHasKey('success', $data);
        $this->assertFalse($data['success']);
        
        // Assert error message
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Building not found', $data['message']);
    }

    public function testGetAllBuildings(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/buildings')
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

        // Assert buildings array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $building = $data['data'][0];
            $this->assertArrayHasKey('id', $building);
            $this->assertArrayHasKey('name', $building);
            $this->assertArrayHasKey('type', $building);
            $this->assertArrayHasKey('settlement_id', $building);
            $this->assertArrayHasKey('level', $building);
            $this->assertArrayHasKey('status', $building);
        }
    }

    public function testGetBuildingsBySettlementId(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/settlements/settlement-001/buildings')
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

        // Assert buildings array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $building = $data['data'][0];
            $this->assertArrayHasKey('id', $building);
            $this->assertArrayHasKey('name', $building);
            $this->assertArrayHasKey('type', $building);
            $this->assertEquals('settlement-001', $building['settlement_id']);
            $this->assertArrayHasKey('level', $building);
            $this->assertArrayHasKey('status', $building);
        }
    }
}
