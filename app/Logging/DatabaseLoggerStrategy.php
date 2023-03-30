<?php

namespace App\Logging;

use App\Models\Log;

class DatabaseLoggerStrategy implements LoggerStrategy
{
    public function log($request, $response)
    {
        $log = new Log();
        $log->request = $request->getContent();
        $log->response = $response->getContent();
        $log->save();
    }
}

class FileLoggerStrategy implements LoggerStrategy
{
    public function log($request, $response)
    {
        $logFileName = date('Y-m-d') . '.log';
        $logFilePath = storage_path("logs/{$logFileName}");
        $logMessage = "[{$request->getMethod()}] {$request->fullUrl()}\n";
        $logMessage .= "Request: {$request->getContent()}\n";
        $logMessage .= "Response: {$response->getContent()}\n\n";
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    }
}
