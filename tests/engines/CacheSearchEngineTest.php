<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Cache\TestCache;
use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @internal
 *
 * @coversNothing
 */
final class CacheSearchEngineTest extends TestCase
{
    private CacheInterface $cache;

    protected function setUp(): void
    {
        $this->cache = new TestCache();
    }

    public function testSuccess(): void
    {
        $searchEngine = $this->createMock(SearchEngineInterface::class);
        $searchEngine->method('search')->willReturnCallback(function () {
            return new GeoDto(
                55.75,
                37.62,
                'Moscow',
                'Moscow City',
                'Russia',
                'Europe/Moscow',
                new \DateTimeImmutable()
            );
        });

        $cacheSearchEngine = new CacheSearchEngine(
            $searchEngine,
            $this->cache,
            3600
        );

        $geoWithoutCache = $cacheSearchEngine->search('Moscow');
        $geoWithCache = $cacheSearchEngine->search('Moscow');
        $geoWithNewKey = $cacheSearchEngine->search('Moscow New Key');

        $this->assertEquals($geoWithoutCache, $geoWithCache);
        $this->assertNotEquals($geoWithNewKey, $geoWithCache);
    }
}
