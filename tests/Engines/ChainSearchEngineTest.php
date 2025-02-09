<?php

declare(strict_types=1);

namespace GeoSearch\Engines;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\SearchEngineInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ChainSearchEngine::class)]
final class ChainSearchEngineTest extends TestCase
{
    public function testSearchSuccess(): void
    {
        $chainSearchEngine = new ChainSearchEngine(
            $this->mockGeoSearchEngines([]),
            $this->mockGeoSearchEngines([]),
            $this->mockGeoSearchEngines($expected = [
                new GeoDto(
                    55.75,
                    37.62,
                    'Moscow',
                    'Moscow City',
                    'Russia',
                    'Europe/Moscow',
                    new \DateTimeImmutable('2023-07-12 0:11')
                ),
            ]),
            $this->mockGeoSearchEngines([]),
            $this->mockGeoSearchEngines([
                new GeoDto(
                    51.52,
                    -0.11,
                    'London',
                    'City of London, Greater London',
                    'United Kingdom',
                    'Europe/London',
                    new \DateTimeImmutable('2023-07-11 22:14')
                ),
            ]),
        );

        $actual = $chainSearchEngine->search('Moscow');

        $this->assertNotNull($actual);
        $this->assertSame($expected, $actual);
    }

    public function testSearchEmptyResult(): void
    {
        $chainSearchEngine = new ChainSearchEngine(
            $this->mockGeoSearchEngines([]),
            $this->mockGeoSearchEngines([]),
            $this->mockGeoSearchEngines([])
        );

        $actual = $chainSearchEngine->search('Moscow');

        $this->assertSame([], $actual);
    }

    private function mockGeoSearchEngines(array $geo): SearchEngineInterface
    {
        $mock = $this->createMock(SearchEngineInterface::class);
        $mock->method('search')->willReturn($geo);

        return $mock;
    }
}
