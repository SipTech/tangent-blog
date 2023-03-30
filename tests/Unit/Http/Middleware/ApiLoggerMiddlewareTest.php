<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Api\V1\ApiLoggerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ApiLoggerMiddlewareTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testApiLoggerMiddlewareLogsRequestAndResponse()
    {
        $logger = Mockery::mock('App\Services\ApiLogger\ApiLoggerInterface');
        $middleware = new ApiLoggerMiddleware($logger);
        $request = Request::create('/api/posts', 'GET', ['param' => 'value']);
        $response = new Response('{"message": "success"}', 200);

        $logger->shouldReceive('logRequest')->once()->with($request);
        $logger->shouldReceive('logResponse')->once()->with($response);

        $middleware->handle($request, function ($req) use ($response) {
            return $response;
        });
    }
}
