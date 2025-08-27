<?php
/**
 * Event Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Event;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class EventEndpointTest extends TestCase
{    
    private $app;
    
    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';
        $this->app = $app;
    }

    public function testGetEventById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/events/event-001')
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

        // Assert event data
        $event = $data['data'];
        $this->assertEquals('event-001', $event['id']);
        $this->assertArrayHasKey('title', $event);
        $this->assertArrayHasKey('description', $event);
        $this->assertArrayHasKey('type', $event);
        $this->assertArrayHasKey('region_id', $event);
        $this->assertArrayHasKey('timestamp', $event);
        $this->assertArrayHasKey('status', $event);
    }

    public function testGetNonExistentEvent(): void
    {
        // Create test request for non-existent event
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/events/non-existent-event')
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
        $this->assertEquals('Event not found', $data['message']);
    }

    public function testGetAllEvents(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/events')
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

        // Assert events array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $event = $data['data'][0];
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('title', $event);
            $this->assertArrayHasKey('description', $event);
            $this->assertArrayHasKey('type', $event);
            $this->assertArrayHasKey('region_id', $event);
            $this->assertArrayHasKey('timestamp', $event);
            $this->assertArrayHasKey('status', $event);
        }
    }
}
