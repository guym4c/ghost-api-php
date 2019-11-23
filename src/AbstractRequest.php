<?php

namespace Guym4c\GhostApiPhp;

use GuzzleHttp;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7;
use Teapot\StatusCode;

abstract class AbstractRequest {

    private const GHOST_APIS_PATH = '/ghost/api';
    private const GHOST_API_VERSION = 'v3';
    private const GHOST_CONTENT_API_PATH = '/content';

    /** @var Ghost */
    protected $ghost;

    /** @var GuzzleHttp\Client */
    protected $http;

    /** @var Psr7\Request */
    protected $request;

    /** @var array */
    protected $options = [];

    public function __construct(Ghost $ghost, string $method, string $uri = '', array $query = [], array $body = []) {

        $this->ghost = $ghost;
        $this->http = new GuzzleHttp\Client();
        $this->request = new Psr7\Request($method,
            sprintf("{$this->ghost->getUrl()}%s/%s%s%s",
                self::GHOST_APIS_PATH,
                self::GHOST_API_VERSION,
                self::GHOST_CONTENT_API_PATH,
                $uri
            )
        );

        $this->options['query'] = array_merge($query, [
            'key' => $this->ghost->getKey(),
        ]);

        if (!empty($body)) {
            $this->options['json'] = $body;
        }

        if ($method != 'GET') {
            $this->request = $this->request->withHeader('Content-Type', 'application/json');
        }
    }

    abstract public function getResponse();

    /**
     * @return array
     * @throws GhostApiException
     */
    protected function execute() {

        try {
            $response = $this->http->send($this->request, $this->options);
        } catch (GuzzleException $e) {
            throw GhostApiException::fromGuzzle($e);
        }

        $responseBody = (string)$response->getBody();
        $responseCode = $response->getStatusCode();

        if ($responseCode !== StatusCode::OK) {
            throw GhostApiException::fromErrorResponse($responseCode, $responseBody);
        }

        return json_decode($responseBody, true);
    }
}