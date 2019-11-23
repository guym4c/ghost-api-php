<?php

namespace Guym4c\GhostApiPhp\Model;

abstract class AbstractContentOwningResource extends AbstractResource {

    public const QUERY_DATA = [
        'include' => 'count.posts',
    ];

    /**
     * @var ?int
     */
    public $countPosts;

    public function __construct(array $json) {
        $this->countPosts = $json['count']['posts'] ?? null;
    }
}