<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\ErrorHandlerInterface;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \Eegusakov\GeoSearch\Engines\MuteSearchEngine
 */
final class MuteSearchEngineTest extends TestCase
{
    public function testSuccess(): void
    {
        $searchEngine = $this->createMock(SearchEngineInterface::class);
        $searchEngine->method('search')->willReturn(
            $expected = new GeoDto(
                55.75,
                37.62,
                'Moscow',
                'Moscow City',
                'Russia',
                'Europe/Moscow',
                new \DateTimeImmutable('2023-07-12 0:11')
            )
        );

        $errorHandler = $this->createMock(ErrorHandlerInterface::class);

        $muteSearchEngine = new MuteSearchEngine(
            $searchEngine,
            $errorHandler
        );

        $actual = $muteSearchEngine->search('Moscow');

        $this->assertSame($expected, $actual);
    }

    public function testError(): void
    {
        $searchEngine = $this->createMock(SearchEngineInterface::class);
        $searchEngine->method('search')->willThrowException(new \Exception());

        $errorHandler = $this->createMock(ErrorHandlerInterface::class);
        $errorHandler->method('handle')->willReturnCallback(static function (): void {
            echo '[ERROR] An error occurred while searching';
        });

        $muteSearchEngine = new MuteSearchEngine(
            $searchEngine,
            $errorHandler
        );

        $muteSearchEngine->search('Moscow');

        $this->expectOutputString('[ERROR] An error occurred while searching');
    }
}
