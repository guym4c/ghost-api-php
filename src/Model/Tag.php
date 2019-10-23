<?php

namespace Guym4c\GhostApiPhp\Model;

class Tag extends AbstractContentOwningResource {

    /** @var string */
    public $name;

    /** @var ?string */
    public $description;

    /** @var ?string */
    public $featureImage;

    /** @var ?string */
    public $visibility;

    /** @var ?string */
    public $metaTitle;

    /** @var ?string */
    public $metaDescription;

    public function __construct(array $json) {
        parent::__construct($json);
        $this->hydrate($json);
    }
}