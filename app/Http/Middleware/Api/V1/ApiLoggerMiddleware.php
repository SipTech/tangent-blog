<?php

namespace App\Http\Middleware\Api\V1;

use App\Logging\LoggerStrategy;
use App\Logging\DatabaseLoggerStrategy;
use App\Logging\FileLoggerStrategy;
use Closure;

class ApiLoggerMiddleware
{
    protected $strategy;

    public function __construct(LoggerStrategy $strategy)
    {
        $this->strategy = $strategy;
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
