<?php

namespace App\Logging;

interface LoggerInterface
{
    /**
     * Log a request and response.
     *
     * @param array $request The request array to log.
     * @param array $response The response array to log.
     * @return void
     */
    public function log(array $request, array $response): void;

    /**
     * Get the logs.
     *
     * @return array
     */
    public function getLogs(): array;
}
