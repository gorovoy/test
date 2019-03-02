<?php

namespace app\services;

use app\models\Log;
use app\services\LoggerInterface;

class Logger
{
    public function log($message)
    {
        $log = new Log();
        $log->message = $message;
        $log->save(false);
    }
} 