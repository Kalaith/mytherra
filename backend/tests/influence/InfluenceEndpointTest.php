<?php
/**
 * Influence Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Influence;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class InfluenceEndpointTest extends TestCase
{    
    private $app;
    
    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';
        $this->app = $app;
    }    public function testCalculateDivineInfluenceCost(): void
    {
        $influenceData = [
            'influence_type' => 'inspirational',
            'target_id' => 'region-001',
            'target_type' => 'region',
            'strength' => 'moderate',
            'description' => 'Test divine influence'
        ];

        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/influence/divine/calculate-cost')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($influenceData));

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

        // Assert cost calculation data
        $costData = $data['data'];
        $this->assertArrayHasKey('cost', $costData);
        $this->assertIsNumeric($costData['cost']);
        $this->assertArrayHasKey('effectivenessEstimate', $costData);
        $this->assertArrayHasKey('targetName', $costData);
        
        // Assert effectiveness estimate structure
        $this->assertIsArray($costData['effectivenessEstimate']);
        $this->assertArrayHasKey('prosperityEffect', $costData['effectivenessEstimate']);
        $this->assertArrayHasKey('heroAttractionModifier', $costData['effectivenessEstimate']);
        $this->assertArrayHasKey('eventProbabilityModifier', $costData['effectivenessEstimate']);
    }    public function testApplyDivineInfluence(): void
    {
        $influenceData = [
            'influence_type' => 'environmental',
            'target_id' => 'region-001',
            'target_type' => 'region',
            'strength' => 'minor',
            'description' => 'Test divine influence application'
        ];

        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/influence/divine/apply')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($influenceData));

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

        // Assert influence result data
        $result = $data['data'];
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('cost', $result);
        $this->assertIsNumeric($result['cost']);
        $this->assertArrayHasKey('effects', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('targetName', $result);
        $this->assertEquals('Arcane Highlands', $result['targetName']);
    }    public function testEmpowerHero(): void
    {
        $empowerData = [
            'hero_id' => 'hero-001',
            'amount' => 10
        ];

        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/influence/hero/empower')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($empowerData));

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

        // Assert empower result
        $result = $data['data'];
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($empowerData['hero_id'], $result['hero_id']);
        $this->assertEquals($empowerData['amount'], $result['amount']);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('empower', $result['type']);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('created_at', $result);
    }    public function testGuideHero(): void
    {
        $guideData = [
            'hero_id' => 'hero-001',
            'destination_id' => 'region-002'
        ];

        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/influence/hero/guide')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($guideData));

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

        // Assert guide result
        $result = $data['data'];
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($guideData['hero_id'], $result['hero_id']);
        $this->assertEquals($guideData['destination_id'], $result['destination_id']);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('guide', $result['type']);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('created_at', $result);
    }    public function testGuideRegionResearch(): void
    {
        $researchData = [
            'region_id' => 'region-001',
            'research_type' => 'landmarks'
        ];

        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/influence/region/guide-research')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($researchData));

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

        // Assert research result
        $result = $data['data'];
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($researchData['region_id'], $result['region_id']);
        $this->assertEquals($researchData['research_type'], $result['research_type']);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('guide-research', $result['type']);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('created_at', $result);
    }
}
