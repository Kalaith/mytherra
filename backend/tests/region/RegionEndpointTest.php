<?php
/**
 * Region Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the database service or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Region;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class RegionEndpointTest extends TestCase
{    
    private $app;

    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';

        $this->app = $app;
    }

    public function testGetRegionById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/regions/region-001')
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
        $this->assertTrue($data['success']);        // Assert region data
        $region = $data['data'];
        $this->assertEquals('region-001', $region['id']);
        $this->assertIsString($region['name']);
        $this->assertIsString($region['cultural_influence']);
        $this->assertIsArray($region['regional_traits']);
        $this->assertIsString($region['climate_type']);
    }

    public function testGetNonExistentRegion(): void
    {
        // Create test request for non-existent region
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/regions/non-existent-region')
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
        $this->assertEquals('Region not found', $data['message']);
    }

    public function testGetAllRegions(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/regions')
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

        // Assert regions array
        $this->assertIsArray($data['data']);
        $this->assertNotEmpty($data['data']);        // Assert first region structure
        $firstRegion = $data['data'][0];
        $this->assertArrayHasKey('id', $firstRegion);
        $this->assertArrayHasKey('name', $firstRegion);
        $this->assertArrayHasKey('cultural_influence', $firstRegion);
        $this->assertArrayHasKey('regional_traits', $firstRegion);
        $this->assertArrayHasKey('climate_type', $firstRegion);
    }
}
