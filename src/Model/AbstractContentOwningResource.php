<?php

namespace Guym4c\GhostApiPhp\Model;

use Exception;
use Guym4c\GhostApiPhp\Ghost;
use Guym4c\GhostApiPhp\GhostApiException;
use Guym4c\GhostApiPhp\Request;

abstract class AbstractContentOwningResource extends AbstractResource {

    public const QUERY_DATA = [
        'include' => 'count.posts',
    ];

    public $countPosts;

    public function __construct(array $json) {
        $this->countPosts = $json['count']['posts'];
    }
}