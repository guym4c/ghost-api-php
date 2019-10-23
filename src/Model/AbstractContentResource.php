<?php

namespace Guym4c\GhostApiPhp\Model;

use DateTime;
use Exception;
use Guym4c\GhostApiPhp\Ghost;
use Guym4c\GhostApiPhp\GhostApiException;
use Guym4c\GhostApiPhp\Request;

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
        $this->primaryTag = new Tag($json['primary_tag']);
        $this->meta = new ContentMeta($json);

        $this->hydrate($json);
    }

    /**
     * @param Ghost  $ghost
     * @param string $uri
     * @return static
     * @throws GhostApiException
     * @throws Exception
     */
    public static function get(Ghost $ghost, string $uri): self {

        $json = (new Request($ghost, 'GET', $uri, [
            'include' => 'tags,authors',
        ]))->getResponse();

        $resources = array_merge($json['resource_type']['pages'] ?? [],
            $json['resource_type']['posts'] ?? []);

        return new self($resources[0]);
    }
}