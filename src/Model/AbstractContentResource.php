<?php

namespace Guym4c\GhostApiPhp\Model;

use DateTime;
use Exception;

class AbstractContentResource extends AbstractResource {

    public const QUERY_DATA = [
        'include' => 'tags,authors',
    ];

    /** @var string */
    public $uuid;

    /** @var string */
    public $title;

    /** @var string */
    public $html;

    /** @var string */
    public $commentId;

    /** @var ?string */
    public $featureImage;

    /** @var bool */
    public $featured;

    /** @var DateTime */
    public $createdAt;

    /** @var ?DateTime */
    public $publishedAt;

    /** @var string */
    public $customExcerpt;

    /** @var ?string */
    public $codeinjectionHead;

    /** @var ?string */
    public $codeinjectionFoot;

    /** @var string */
    public $customTemplate;

    /** @var ?string */
    public $canonicalUrl;

    /** @var bool */
    public $page;

    /** @var Tag[] */
    public $tags;

    /** @var Author[] */
    public $authors;

    /** @var Author */
    public $primaryAuthor;

    /** @var Tag */
    public $primaryTag;

    /** @var string */
    public $excerpt;

    /** @var ContentMeta */
    public $meta;

    /**
     * Content constructor.
     * @param array $json
     * @throws Exception
     */
    public function __construct(array $json) {

        $this->createdAt = new DateTime($json['created_at']);

        if (!empty($json['published_at'])) {
            $this->publishedAt = new DateTime($json['published_at']);
        }

        $this->populateArrayType(Author::class, 'authors', $json);
        $this->populateArrayType(Tag::class, 'tags', $json);

        $this->primaryAuthor = new Author($json['primary_author']);

        if (!empty($json['primary_tag'])) {
            $this->primaryTag = new Tag($json['primary_tag']);
        }

        $this->meta = new ContentMeta($json);

        $this->hydrate($json);
    }
}