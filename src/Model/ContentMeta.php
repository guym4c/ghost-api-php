<?php

namespace Guym4c\GhostApiPhp\Model;

class ContentMeta extends AbstractModel {

    public $title;

    public $description;

    public $ogImage;

    public $ogTitle;

    public $ogDescription;

    public $twitterImage;

    public $twitterTitle;

    public $twitterDescription;

    public function __construct(array $json) {
        $this->title = $json['meta_title'];
        $this->description = $json['meta_description'];
        $this->hydrate($json);
    }
}