<?php
/**
 * Landmark Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Landmark;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class LandmarkEndpointTest extends TestCase
{    
    private $app;
    
    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';
        $this->app = $app;
    }    public function testGetLandmarkById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/landmarks/landmark-001')
            ->withHeader('Accept', 'application/json');        // Handle the request
        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        // Assert response status code
        $this->assertEquals(200, $response->getStatusCode());

        // Assert response structure
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertTrue($data['success']);

        // Assert landmark data
        $landmark = $data['data'];
        $this->assertEquals('landmark-001', $landmark['id']);
        $this->assertArrayHasKey('name', $landmark);
        $this->assertArrayHasKey('type', $landmark);
        $this->assertArrayHasKey('region_id', $landmark);
        $this->assertArrayHasKey('description', $landmark);
        $this->assertArrayHasKey('status', $landmark);
    }

    public function testGetNonExistentLandmark(): void
    {
        // Create test request for non-existent landmark
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/landmarks/non-existent-landmark')
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
        $this->assertEquals('Landmark not found', $data['message']);
    }

    public function testGetAllLandmarks(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/landmarks')
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

        // Assert landmarks array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $landmark = $data['data'][0];
            $this->assertArrayHasKey('id', $landmark);
            $this->assertArrayHasKey('name', $landmark);
            $this->assertArrayHasKey('type', $landmark);
            $this->assertArrayHasKey('region_id', $landmark);
            $this->assertArrayHasKey('description', $landmark);
            $this->assertArrayHasKey('status', $landmark);
        }
    }

    public function testGetLandmarksByRegionId(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/regions/region-001/landmarks')
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

        // Assert landmarks array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $landmark = $data['data'][0];
            $this->assertArrayHasKey('id', $landmark);
            $this->assertArrayHasKey('name', $landmark);
            $this->assertArrayHasKey('type', $landmark);
            $this->assertEquals('region-001', $landmark['region_id']);
            $this->assertArrayHasKey('description', $landmark);
            $this->assertArrayHasKey('status', $landmark);
        }
    }
}
