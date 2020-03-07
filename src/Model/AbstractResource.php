<?php

namespace Guym4c\GhostApiPhp\Model;

use Guym4c\GhostApiPhp\CollectionRequest;
use Guym4c\GhostApiPhp\Filter;
use Guym4c\GhostApiPhp\Ghost;
use Guym4c\GhostApiPhp\GhostApiException;
use Guym4c\GhostApiPhp\Request;
use Guym4c\GhostApiPhp\Sort;

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
     * @param Sort|null   $sort
     * @param Filter|null $filter
     * @return CollectionRequest
     * @throws GhostApiException
     */
    public static function get(Ghost $ghost, int $limit = null, Sort $sort = null, Filter $filter = null): CollectionRequest {

        $query = [];
        $query['filter'] = empty($filter)
            ? null
            : (string)$filter;

        $query['sort'] = empty($sort)
            ? null
            : (string)$sort;

        return (new CollectionRequest($ghost,
            'GET',
            static::class,
            self::getResourceName(),
            '/' . self::getResourceName(),
            $limit,
            array_merge($query, self::getQueryData()))
        )->getResponse();
    }

    /**
     * @param Ghost  $ghost
     * @param string $id
     * @return AbstractResource
     * @throws GhostApiException
     */
    public static function byId(Ghost $ghost, string $id) {
        return self::getResource($ghost, "/{$id}/");
    }

    /**
     * @param Ghost  $ghost
     * @param string $slug
     * @return static
     * @throws GhostApiException
     */
    public static function bySlug(Ghost $ghost, string $slug) {
        return self::getResource($ghost, "/slug/{$slug}/");
    }

    /**
     * @param Ghost  $ghost
     * @param string $uri
     * @return static
     * @throws GhostApiException
     */
    private static function getResource(Ghost $ghost, string $uri) {
        $uri = self::getResourceName() . $uri;
        $cache = $ghost->getCache();

        if ($cache
            && $cache->contains($uri)
        ) {
            return $cache->fetch($uri);
        }

        try {
            $json = (new Request($ghost, 'GET', "/{$uri}", self::getQueryData()))
                ->getResponse();
        } catch (GhostApiException $e) {
            if (strpos($e->getMessage(), 'NotFoundError') !== false) {
                return null;
            }
            throw $e;
        }

        $resource = new static($json[self::getResourceName()][0]);

        if ($cache) {
            $cache->save($uri, $resource, $ghost->getCacheLifetime());
        }

        return $resource;
    }

    protected static function getResourceName() {
        return static::RESOURCE_NAME;
    }

    protected static function getQueryData() {
        return static::QUERY_DATA;
    }
}