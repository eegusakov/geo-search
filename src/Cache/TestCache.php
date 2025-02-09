<?php

declare(strict_types=1);

namespace GeoSearch\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * This class is used exclusively for writing Unit tests for the CacheSearchEngine functionality.
 *
 * @internal
 *
 * @codeCoverageIgnore
 */
final class TestCache implements CacheInterface
{
    private array $cache;

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache[$key] ?? null;
    }

    public function set(string $key, mixed $value, null|\DateInterval|int $ttl = null): bool
    {
        if (null === $this->get($key)) {
            $this->cache[$key] = $value;

            return true;
        }

        return false;
    }

    public function delete(string $key): bool
    {
        return false;
    }

    public function clear(): bool
    {
        return false;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return null;
    }

    public function setMultiple(iterable $values, null|\DateInterval|int $ttl = null): bool
    {
        return false;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return false;
    }

    public function has(string $key): bool
    {
        return false;
    }
}
