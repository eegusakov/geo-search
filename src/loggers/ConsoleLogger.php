<?php

namespace Eegusakov\GeoSearch\Loggers;

use Psr\Log\LoggerInterface;

/**
 * A class for displaying logs to the console
 */
class ConsoleLogger implements LoggerInterface
{
    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        echo "\e[31m[EMERGENCY] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function alert(\Stringable|string $message, array $context = []): void
    {
        echo "\e[34m[ALERT] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function critical(\Stringable|string $message, array $context = []): void
    {
        echo "\e[31m[CRITICAL] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function error(\Stringable|string $message, array $context = []): void
    {
        echo "\e[31m[ERROR] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function warning(\Stringable|string $message, array $context = []): void
    {
        echo "\e[33m[WARNING] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function notice(\Stringable|string $message, array $context = []): void
    {
        echo "\e[33m[NOTICE] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function info(\Stringable|string $message, array $context = []): void
    {
        echo "\e[39m[INFO] $message" . PHP_EOL;
    }

    /**
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function debug(\Stringable|string $message, array $context = []): void
    {
        echo "\e[39m[DEBUG] $message" . PHP_EOL;
    }

    /**
     * @param $level
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        echo "\e[39m[LOG][$level] $message" . PHP_EOL;
    }
}