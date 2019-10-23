<?php

namespace Guym4c\GhostApiPhp;

class Request extends AbstractRequest {

    /**
     * @return array
     * @throws GhostApiException
     */
    public function getResponse(): array {
        return $this->execute();
    }
}