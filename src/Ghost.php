<?php

namespace Guym4c\GhostApiPhp;

use Exception;
use Guym4c\GhostApiPhp\Model\AbstractContentResource;

class Ghost {

    private $key;

    private $url;

    public function __construct(string $baseUrl, string $key) {
        $this->key = $key;
        $this->url = $baseUrl;
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
}