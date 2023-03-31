<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Api\V1\ApiLoggerMiddleware;
use App\Logging\DatabaseLoggerStrategy;
use App\Logging\FileLoggerStrategy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class ApiLoggerMiddlewareTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testApiLoggerMiddlewareLogsRequestsAndResponses()
    {
        // Create a mock request and response
        $request = Request::create('/api/v1/foo', 'GET');
        $response = new Response('Hello World');

        // Create a logger strategy instance based on your configuration
        $loggerStrategy = Config::get('app.log_strategy') === 'file' ? new FileLoggerStrategy() : new DatabaseLoggerStrategy();

        // Create a new instance of the middleware and pass in the logger strategy
        $middleware = new ApiLoggerMiddleware($loggerStrategy);

        // Invoke the middleware and get the logged data
        $middleware->handle($request, function ($req) use ($response) {
            return $response;
        });

        // Assert that the request and response were logged
        $logs = $loggerStrategy->getLogs();

        // Assert that the logs array has at least one entry
        $this->assertNotEmpty($logs);

        // Assert that the first log entry contains the request method and URI
        $this->assertStringContainsString($request->getMethod(), $logs[0]['request']['method']);
        $this->assertStringContainsString($request->getUri(), $logs[0]['request']['uri']);
        $this->assertStringContainsString((string) $response->getStatusCode(), $logs[0]['response']['status_code']);
        $this->assertStringContainsString($response->getContent(), $logs[0]['response']['content']);

    }
}
