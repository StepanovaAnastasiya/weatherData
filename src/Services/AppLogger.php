<?php

namespace Weather\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class AppLogger

{
    public $logger;


    public function __construct()
    {
        $this->logger = new Logger('AppLogger');
        $logFileHandler = new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::DEBUG);
        $logFileHandler->setFormatter(new LineFormatter(null, null, true, true));
        $this->logger->pushHandler($logFileHandler);
    }

}