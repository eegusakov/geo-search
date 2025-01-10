<?php

declare(strict_types=1);

namespace GeoSearch\Loggers;

use Psr\Log\LoggerInterface;

/**
 * A class for displaying logs to the console.
 */
final class ConsoleLogger implements LoggerInterface
{
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        echo "\e[31m[EMERGENCY] {$message}" . PHP_EOL;
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        echo "\e[34m[ALERT] {$message}" . PHP_EOL;
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        echo "\e[31m[CRITICAL] {$message}" . PHP_EOL;
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        echo "\e[31m[ERROR] {$message}" . PHP_EOL;
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        echo "\e[33m[WARNING] {$message}" . PHP_EOL;
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        echo "\e[33m[NOTICE] {$message}" . PHP_EOL;
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        echo "\e[39m[INFO] {$message}" . PHP_EOL;
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        echo "\e[39m[DEBUG] {$message}" . PHP_EOL;
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        echo "\e[39m[LOG][{$level}] {$message}" . PHP_EOL;
    }
}
