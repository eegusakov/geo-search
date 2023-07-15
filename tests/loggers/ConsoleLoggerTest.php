<?php

namespace Eegusakov\GeoSearch\Loggers;

use PHPUnit\Framework\TestCase;

class ConsoleLoggerTest extends TestCase
{
    /** @var ConsoleLogger $logger */
    private ConsoleLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new ConsoleLogger();
    }

    /**
     * @dataProvider messageProvider
     * @param string $methodName
     * @param string $expected
     * @return void
     */
    public function testPrintMessage(string $methodName, string $expected): void
    {
        $this->logger->{$methodName}($methodName);

        $this->expectOutputString($expected);
    }

    /**
     * @return void
     */
    public function testPrintMessageLogWithLevel(): void
    {
        $this->logger->log(3, 'level three');

        $this->expectOutputString("\e[39m[LOG][3] level three" . PHP_EOL);
    }

    public function messageProvider(): \Generator
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
}
