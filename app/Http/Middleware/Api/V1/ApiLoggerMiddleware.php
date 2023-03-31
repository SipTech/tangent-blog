<?php

namespace App\Http\Middleware\Api\V1;

use App\Logging\LoggerStrategy;
use App\Logging\DatabaseLoggerStrategy;
use App\Logging\FileLoggerStrategy;
use Illuminate\Http\Response;;
use Illuminate\Http\Request;
use Closure;

class ApiLoggerMiddleware
{
    protected $strategy;

    protected function logResponse(Response $response, array &$log): void
    {
        $log['response'] = [
            'status_code' => $response->getStatusCode(),
            'content' => $response->getContent(),
        ];
    }
    public function __construct(LoggerStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function log(Request $request, Response $response): void
    {
        $this->logResponse($response, ['response' => []]);
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (config('app.env') !== 'production') {
            $this->strategy->log($request, $response);
        }

        return $response;
    }
}
