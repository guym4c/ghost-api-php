<?php

namespace Guym4c\GhostApiPhp;

class Ghost {

    private const DEFAULT_API_VERSION = 'v3';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $version;

    public function __construct(string $baseUrl, string $key, ?string $version = null) {
        $this->key = $key;
        $this->url = $baseUrl;
        $this->version = $version ?? self::DEFAULT_API_VERSION;
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
        return $this->version;
    }
}