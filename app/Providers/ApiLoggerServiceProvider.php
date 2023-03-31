<?php

namespace App\Providers;

use App\Http\Middleware\Api\V1\ApiLoggerMiddleware;
use App\Logging\DatabaseLoggerStrategy;
use App\Logging\FileLoggerStrategy;
use Illuminate\Support\ServiceProvider;

class ApiLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(LoggerStrategy::class, function ($app) {
            $logStrategy = config('app.log_strategy');
            if ($logStrategy === 'file') {
                return new FileLoggerStrategy();
            } elseif ($logStrategy === 'database') {
                return new DatabaseLoggerStrategy();
            }
            // Default to file logging if no valid strategy found
            return new FileLoggerStrategy();
        });
    }
}
