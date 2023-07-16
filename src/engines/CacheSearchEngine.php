<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * A class that allows you to receive write and retrieve data from the cache.
 */
final class CacheSearchEngine implements SearchEngineInterface
{
    public function __construct(
        private SearchEngineInterface $next,
        private CacheInterface $cache,
        private int $ttl,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function search(string $query): ?GeoDto
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
