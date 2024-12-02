<?php

declare(strict_types=1);

namespace App\Domain;

use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class CalculatorLogger
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        // $stream_handler = new StreamHandler(__DIR__ . "/../var/logs/error.log");
        // $this->logger->pushHandler($stream_handler);
    }

    public function logError(string $msg, array $context = []): void
    {
        $this->logger->error($msg, $context);

    }
}
