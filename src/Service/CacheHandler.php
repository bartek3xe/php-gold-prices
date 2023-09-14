<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheHandler
{
    private FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public function setCacheDirectory(string $cacheDirectory): void
    {
        $this->cache = new FilesystemAdapter('', 0, $cacheDirectory);
    }

    public function getOrSet(string $cacheKey, callable $valueCallback, int $ttl = 3600): mixed
    {
        $cachedResult = $this->cache->getItem($cacheKey);

        if (!$cachedResult->isHit()) {
            $result = $valueCallback();

            $cachedResult->set($result);
            $cachedResult->expiresAfter($ttl);

            $this->cache->save($cachedResult);
        } else {
            $result = $cachedResult->get();
        }

        return $result;
    }

    public function clear(): void
    {
        $this->cache->clear();
    }
}
