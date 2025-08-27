<?php
/**
 * Settlement Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Settlement;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class SettlementEndpointTest extends TestCase
{    
    private $app;
    
    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';
        $this->app = $app;
    }

    public function testGetSettlementById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/settlements/settlement-001')
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
        $this->assertTrue($data['success']);        // Assert settlement data
        $settlement = $data['data'];
        $this->assertEquals('settlement-001', $settlement['id']);
        $this->assertArrayHasKey('name', $settlement);
        $this->assertArrayHasKey('type', $settlement);
        $this->assertArrayHasKey('region_id', $settlement);
        $this->assertArrayHasKey('status', $settlement);
        $this->assertArrayHasKey('population', $settlement);
        $this->assertArrayHasKey('prosperity', $settlement);
    }

    public function testGetNonExistentSettlement(): void
    {
        // Create test request for non-existent settlement
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/settlements/non-existent-settlement')
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
        $this->assertEquals('Settlement not found', $data['message']);
    }

    public function testGetAllSettlements(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/settlements')
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
        $this->assertTrue($data['success']);        // Assert settlements array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $settlement = $data['data'][0];
            $this->assertArrayHasKey('id', $settlement);
            $this->assertArrayHasKey('name', $settlement);
            $this->assertArrayHasKey('type', $settlement);
            $this->assertArrayHasKey('region_id', $settlement);
            $this->assertArrayHasKey('status', $settlement);
            $this->assertArrayHasKey('population', $settlement);
            $this->assertArrayHasKey('prosperity', $settlement);
        }
    }
}
