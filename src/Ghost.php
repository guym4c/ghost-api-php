<?php

namespace Guym4c\GhostApiPhp;

use Doctrine\Common\Cache\CacheProvider;

class Ghost {

    private const API_VERSION = 'v3';

    private const DEFAULT_CACHE_LIFETIME = 60 * 15; // 15 minutes;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $url;

    /**
     * @var CacheProvider|null
     */
    private $cache;

    /**
     * @var int
     */
    private $cacheLifetime;

    public function __construct(string $baseUrl, string $key, ?CacheProvider $cache = null, ?int $cacheLifetime = null) {
        $this->key = $key;
        $this->url = $baseUrl;
        $this->cache = $cache;
        $this->cacheLifetime = $cacheLifetime ?? self::DEFAULT_CACHE_LIFETIME;
    }

    /**
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string {
        return self::API_VERSION;
    }

    /**
     * @return CacheProvider|null
     */
    public function getCache(): ?CacheProvider {
        return $this->cache;
    }

    /**
     * @return bool
     */
    public function isCaching(): bool {
        return $this->cache !== null;
    }

    /**
     * @return int
     */
    public function getCacheLifetime(): int {
        return $this->cacheLifetime;
    }

    /**
     * @return bool
     */
    public function flushCache(): bool {
        return $this->cache->flushAll();
    }
}