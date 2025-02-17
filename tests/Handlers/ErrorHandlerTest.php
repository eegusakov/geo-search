<?php

declare(strict_types=1);

namespace GeoSearch\Handlers;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 *
 * @covers \GeoSearch\Handlers\ErrorHandler
 */
final class ErrorHandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('error')->willReturnCallback(static function (): void {
            echo '[ERROR] An error occurred while searching';
        });

        $errorHandler = new ErrorHandler($logger);

        $errorHandler->handle(new \Exception());

        $this->expectOutputString('[ERROR] An error occurred while searching');
    }
}
