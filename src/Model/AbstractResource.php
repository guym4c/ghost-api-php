<?php

namespace Guym4c\GhostApiPhp\Model;

use Guym4c\GhostApiPhp\CollectionRequest;
use Guym4c\GhostApiPhp\Filter;
use Guym4c\GhostApiPhp\Ghost;
use Guym4c\GhostApiPhp\GhostApiException;
use Guym4c\GhostApiPhp\Request;

abstract class AbstractResource extends AbstractModel {

    /** @var string */
    public $id;

    /** @var string */
    public $slug;

    /** @var string */
    public $url;

    abstract public function __construct(array $json);

    /**
     * @param Ghost       $ghost
     * @param int         $limit
     * @param Filter|null $filter
     * @return CollectionRequest
     * @throws GhostApiException
     */
    public static function get(Ghost $ghost, int $limit = null, Filter $filter = null): CollectionRequest {

        $query = empty($filter)
            ? []
            : ['filter' => (string)$filter];

        return (new CollectionRequest($ghost,
            'GET',
            static::class,
            self::getResourceName(),
            '/' . self::getResourceName(),
            $limit,
            $query)
        )->getResponse();
    }

    /**
     * @param Ghost  $ghost
     * @param string $id
     * @return AbstractResource
     * @throws GhostApiException
     */
    public static function byId(Ghost $ghost, string $id) {
        return self::getResource($ghost, "/{$id}");
    }

    /**
     * @param Ghost  $ghost
     * @param string $slug
     * @return static
     * @throws GhostApiException
     */
    public static function bySlug(Ghost $ghost, string $slug) {
        return self::getResource($ghost, "/slug/{$slug}");
    }

    /**
     * @param Ghost  $ghost
     * @param string $uri
     * @return static
     * @throws GhostApiException
     */
    private static function getResource(Ghost $ghost, string $uri){
        $json = (new Request($ghost, 'GET', '/' . self::getResourceName() . $uri, self::getQueryData()))
            ->getResponse();

        $resources = array_merge($json['resource_type']['pages'] ?? [],
            $json['resource_type']['posts'] ?? []);

        return new static($resources['resource_type'][self::getResourceName()][0]);
    }

    protected static function getResourceName() {
        return static::RESOURCE_NAME;
    }

    protected static function getQueryData() {
        return static::QUERY_DATA;
    }
}