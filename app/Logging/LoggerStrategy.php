<?php

namespace App\Logging;

interface LoggerStrategy
{
    public function log($request, $response);
}
