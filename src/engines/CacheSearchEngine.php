<?php

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * A class that allows you to receive write and retrieve data from the cache
 */
class CacheSearchEngine implements SearchEngineInterface
{
    /**
     * @param SearchEngineInterface $next
     * @param CacheInterface $cache
     * @param int $ttl
     */
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
        $geo = $this->cache->get($query);

        if (null === $geo) {
            $geo = $this->next->search($query);
            $this->cache->set($query, $geo, $this->ttl);
        }

       return $geo;
    }
}