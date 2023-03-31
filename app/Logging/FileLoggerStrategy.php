<?php

namespace App\Logging;

use Illuminate\Support\Facades\File;

class FileLoggerStrategy implements LoggerStrategy
{
    private $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('logs/api.log');
    }

    public function log($request, $response)
    {
        $log = [
            'request' => [
                'method' => $request->getMethod(),
                'url' => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'body' => $request->all(),
            ],
            'response' => [
                'status_code' => $response->getStatusCode(),
                'headers' => $response->headers->all(),
                'body' => $response->getContent(),
            ],
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->logToFile($log);
    }

    private function logToFile($log)
    {
        $logString = json_encode($log) . PHP_EOL;
        file_put_contents($this->filePath, $logString, FILE_APPEND | LOCK_EX);
    }

    public function getLogs()
    {
        $logs = file_get_contents($this->filePath);
        $logs = explode(PHP_EOL, $logs);
        $logs = array_filter($logs);

        return array_map(function ($log) {
            return json_decode($log, true);
        }, $logs);
    }
}
