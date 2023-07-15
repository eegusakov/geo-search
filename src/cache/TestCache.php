<?php

namespace Eegusakov\GeoSearch\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * This class is used exclusively for writing Unit tests for the CacheSearchEngine functionality.
 */
class TestCache implements CacheInterface
{
    private array $cache;

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param \DateInterval|int|null $ttl
     * @return bool
     */
    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        if ($this->get($key) === null) {
            $this->cache[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        return false;
    }

    /**
     * @param iterable $keys
     * @param mixed|null $default
     * @return iterable
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return null;
    }

    /**
     * @param iterable $values
     * @param \DateInterval|int|null $ttl
     * @return bool
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        return false;
    }

    /**
     * @param iterable $keys
     * @return bool
     */
    public function deleteMultiple(iterable $keys): bool
    {
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return false;
    }
}