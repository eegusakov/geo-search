<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Handlers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 *
 * @covers \Eegusakov\GeoSearch\Handlers\ErrorHandler
 */
final class ErrorHandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('error')->willReturnCallback(function (): void {
            echo '[ERROR] An error occurred while searching';
        });

        $errorHandler = new ErrorHandler($logger);

        $errorHandler->handle(new \Exception());

        $this->expectOutputString('[ERROR] An error occurred while searching');
    }
}
