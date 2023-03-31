<?php

namespace App\Logging;

use App\Models\Log;
use Illuminate\Support\Facades\DB;

class DatabaseLoggerStrategy implements LoggerStrategy
{
    public function log($request, $response)
    {
        $log = new Log();
        $log->request = $request->getContent();
        $log->response = $response->getContent();
        $log->save();
    }

    public function getLogs()
    {
        $logs = DB::table('logs')->orderBy('id', 'desc')->get();
        return $logs;
    }

}
