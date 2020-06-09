<?php

namespace Guym4c\GhostApiPhp;

use ErrorException;
use Exception;
use Guym4c\GhostApiPhp\Model\AbstractResource;
use Iterator;

class CollectionRequest extends AbstractRequest implements Iterator {

    private const DEFAULT_BROWSE_LIMIT = 15;

    /** @var string */
    private $resource;

    /** @var string */
    private $resourceType;

    /** @var AbstractResource[] */
    private $resources;

    /** @var ?int */
    private $page;

    /** @var ?int */
    private $numPages;

    public function __construct(Ghost $ghost, string $method, string $resource, string $resourceType, string $uri = '', int $limit = null, array $query = [], array $body = []) {
        parent::__construct($ghost, $method, $uri, $query, $body);
        $this->resource = $resource;
        $this->resourceType = $resourceType;

        $this->options['query']['limit'] = $limit ?? self::DEFAULT_BROWSE_LIMIT;
    }

    /**
     * @return $this
     * @throws GhostApiException
     */
    public function getResponse(): self {

        $json = $this->execute();

        $this->resources = [];
        foreach ($json[$this->resourceType] as $jsonResource) {
            $this->resources[] = new $this->resource($jsonResource);
        }

        $this->page = $json['meta']['pagination']['page'];
        $this->numPages = $json['meta']['pagination']['pages'];

        return $this;
    }

    /**
     * @return $this
     * @throws ErrorException
     */
    public function nextPage(): self {
        $this->next();
        if ($this->valid()) {
            return $this;
        } else {
            throw new ErrorException("Tried to reach an invalid page");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void {
        $this->options['query']['page'] = ++$this->page;
        try {
            $this->getResponse();
        } catch (Exception $e) {}
    }

    /**
     * @return array
     */
    public function getResources(): array {
        return $this->resources;
    }

    /**
     * {@inheritDoc}
     */
    public function current(): array {
        return $this->resources;
    }

    /**
     * {@inheritDoc}
     */
    public function key() {
        return $this->page;
    }

    /**
     * {@inheritDoc}
     */
    public function valid() {
        return $this->page <= $this->numPages;
    }

    /**
     * {@inheritDoc}
     * @throws GhostApiException
     */
    public function rewind() {
        $this->options['query']['page'] = 0;
        $this->getResponse();
    }

    /**
     * @return int|null
     */
    public function getNumPages(): ?int {
        return $this->numPages;
    }
}