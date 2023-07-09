<?php

namespace Eegusakov\GeoSearch;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * A class that allows you to receive write and retrieve data from the cache
 */
class CacheGeoSearch implements GeoSearchInterface
{
    /**
     * @param GeoSearchInterface $next
     * @param CacheInterface $cache
     * @param int $ttl
     */
    public function __construct(
      private GeoSearchInterface $next,
      private CacheInterface $cache,
      private int $ttl,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function search(string $query): ?GeoDto
    {
       if ($this->cache->has($query)) {
           return $this->cache->get($query);
       }

       $geo = $this->next->search($query);
       if (null !== $geo) {
           $this->cache->set($query, $geo, $this->ttl);

           return $geo;
       }

       return null;
    }
}