<?php

namespace Guym4c\GhostApiPhp\Model;

class Author extends AbstractContentOwningResource {

    public const RESOURCE_NAME = 'authors';

    /** @var string */
    public $name;

    /** @var ?string */
    public $profileImage;

    /** @var ?string */
    public $coverImage;

    /** @var string */
    public $bio;

    /** @var ?string */
    public $website;

    /** @var ?string */
    public $location;

    /** @var ?string */
    public $facebook;

    /** @var ?string */
    public $twitter;

    /** @var ?string */
    public $metaTitle;

    /** @var ?string */
    public $metaDescription;

    public function __construct(array $json) {
        parent::__construct($json);
        $this->hydrate($json);
    }
}