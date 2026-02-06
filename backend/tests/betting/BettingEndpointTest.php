<?php
/**
 * Betting Endpoint Tests
 * 
 * IMPORTANT: This test suite relies on the application setup from index.php.
 * DO NOT try to recreate the container, database service, or middleware here.
 * The app instance from index.php already has everything we need set up.
 */

namespace Tests\Betting;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class BettingEndpointTest extends TestCase
{    
    private $app;
    
    protected function setUp(): void
    {
        // Import the app setup from index.php which handles all initialization
        require __DIR__ . '/../../public/index.php';
        $this->app = $app;
    }

    protected function tearDown(): void
    {
        // Clean up any test data if necessary
        // Ideally we would rollback transaction here
    }

    public function testGetAllDivineBets(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/bets')
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
        $this->assertTrue($data['success']);        // Assert bets array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $bet = $data['data'][0];
            $this->assertArrayHasKey('id', $bet);
            $this->assertArrayHasKey('betType', $bet);
            $this->assertArrayHasKey('divineFavorStake', $bet);
            $this->assertArrayHasKey('currentOdds', $bet);
            $this->assertArrayHasKey('status', $bet);
            $this->assertArrayHasKey('targetId', $bet);
            $this->assertArrayHasKey('placedYear', $bet);
        }
    }    public function testGetDivineBetById(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/bets/bet-001')
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

        // Assert bet data
        $bet = $data['data'];
        $this->assertEquals('bet-001', $bet['id']);
        $this->assertArrayHasKey('betType', $bet);
        $this->assertArrayHasKey('divineFavorStake', $bet);
        $this->assertArrayHasKey('currentOdds', $bet);
        $this->assertArrayHasKey('status', $bet);
        $this->assertArrayHasKey('targetId', $bet);
        $this->assertArrayHasKey('placedYear', $bet);
    }public function testGetBettingOdds(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/betting-odds')
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
        $this->assertTrue($data['success']);        // Assert odds data - should be an object with bet types as keys
        $odds = $data['data'];
        $this->assertIsArray($odds);
        // Check for at least one of the expected bet types
        $expectedBetTypes = ['settlement_growth', 'landmark_discovery', 'cultural_shift'];
        $hasExpectedType = false;
        foreach ($expectedBetTypes as $betType) {
            if (isset($odds[$betType])) {
                $betTypeOdds = $odds[$betType];
                $this->assertArrayHasKey('probability', $betTypeOdds);
                $this->assertArrayHasKey('payout', $betTypeOdds);
                $this->assertArrayHasKey('confidence', $betTypeOdds);
                $this->assertIsNumeric($betTypeOdds['probability']);
                $this->assertIsNumeric($betTypeOdds['payout']);
                $hasExpectedType = true;
                break;
            }
        }
        $this->assertTrue($hasExpectedType, 'Expected at least one bet type in odds response');
    }    public function testGetSpeculationEvents(): void
    {
        // Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/speculation-events')
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
        $this->assertTrue($data['success']);        // Assert events array
        $this->assertIsArray($data['data']);
        if (!empty($data['data'])) {
            $event = $data['data'][0];
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('type', $event);
            $this->assertArrayHasKey('status', $event);
            $this->assertArrayHasKey('description', $event);
        }
    }    public function testPlaceDivineBet(): void
    {
        $betData = [
            'betType' => 'settlement_growth',
            'targetId' => 'settlement-001',
            'description' => 'Test settlement will grow within timeframe',
            'timeframe' => 5,
            'confidence' => 'possible',
            'divineFavorStake' => 100
        ];// Create test request
        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/bets')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($betData));

        // Handle the request
        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        // Assert response status code
        $this->assertEquals(200, $response->getStatusCode());

        // Assert response structure
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertTrue($data['success']);        // Assert bet data
        $bet = $data['data'];
        $this->assertArrayHasKey('id', $bet);
        $this->assertEquals($betData['targetId'], $bet['targetId']);
        $this->assertEquals($betData['divineFavorStake'], $bet['divineFavorStake']);
        $this->assertEquals($betData['betType'], $bet['betType']);
        $this->assertArrayHasKey('currentOdds', $bet);
        $this->assertArrayHasKey('status', $bet);
        $this->assertArrayHasKey('placedYear', $bet);
    }

    public function testPlaceDivineBetValidationFailure(): void
    {
        // Missing required fields
        $betData = [];

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/bets')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($betData));

        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertEquals('VALIDATION_ERROR', $data['error']['code']);
    }

    public function testPlaceDivineBetInvalidType(): void
    {
        $betData = [
            'betType' => 'invalid_type', // Invalid
            'targetId' => 'settlement-001',
            'description' => 'Test',
            'timeframe' => 5,
            'confidence' => 'possible',
            'divineFavorStake' => 100
        ];

        $request = (new ServerRequestFactory)
            ->createServerRequest('POST', '/api/bets')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        $request->getBody()->write(json_encode($betData));

        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertFalse($data['success']);
    }

    public function testGetNonExistentBet(): void
    {
        $request = (new ServerRequestFactory)
            ->createServerRequest('GET', '/api/bets/non-existent-id')
            ->withHeader('Accept', 'application/json');

        $response = $this->app->handle($request);
        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        // Should return 200 with success=false per ApiResponseTrait logic for ResourceNotFoundException
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFalse($data['success']);
        $this->assertStringContainsString('not found', strtolower($data['message']));
    }
}
