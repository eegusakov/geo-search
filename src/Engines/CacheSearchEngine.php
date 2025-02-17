<?php

declare(strict_types=1);

namespace GeoSearch\Engines;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\SearchEngineInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * A class that allows you to receive write and retrieve data from the cache.
 */
final readonly class CacheSearchEngine implements SearchEngineInterface
{
    public function __construct(
        private SearchEngineInterface $next,
        private CacheInterface $cache,
        private int $ttl,
    ) {}

    /**
     * @return array<empty>|GeoDto[]
     *
     * @throws InvalidArgumentException
     */
    public function search(string $query): array
    {
        $key = 'geo_search_' . str_replace(' ', '_', $query);

        $geo = $this->cache->get($key);

        if (null === $geo) {
            $geo = $this->next->search($query);
            $this->cache->set($key, $geo, $this->ttl);
        }

        return $geo;
    }
}
