<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Loggers;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \Eegusakov\GeoSearch\Loggers\ConsoleLogger
 */
final class ConsoleLoggerTest extends TestCase
{
    private ConsoleLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new ConsoleLogger();
    }

    public static function providePrintMessageCases(): iterable
    {
        yield ['emergency', "\e[31m[EMERGENCY] emergency" . PHP_EOL];
        yield ['alert', "\e[34m[ALERT] alert" . PHP_EOL];
        yield ['critical', "\e[31m[CRITICAL] critical" . PHP_EOL];
        yield ['error', "\e[31m[ERROR] error" . PHP_EOL];
        yield ['warning', "\e[33m[WARNING] warning" . PHP_EOL];
        yield ['notice', "\e[33m[NOTICE] notice" . PHP_EOL];
        yield ['info', "\e[39m[INFO] info" . PHP_EOL];
        yield ['debug', "\e[39m[DEBUG] debug" . PHP_EOL];
    }

    /**
     * @dataProvider providePrintMessageCases
     */
    public function testPrintMessage(string $methodName, string $expected): void
    {
        $this->logger->{$methodName}($methodName);

        $this->expectOutputString($expected);
    }

    public function testPrintMessageLogWithLevel(): void
    {
        $this->logger->log(3, 'level three');

        $this->expectOutputString("\e[39m[LOG][3] level three" . PHP_EOL);
    }
}
