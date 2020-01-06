<?php

namespace Guym4c\GhostApiPhp;

class Sort {

    /** @var string */
    private $field;

    /** @var string */
    private $order = SortOrder::ASC;

    /**
     * Sort constructor.
     * @param $field
     * @param $order string enum available
     * @see SortOrder
     */
    public function __construct($field, $order) {
        $this->field = $field;
        $this->order = $order;
    }

    public function __toString(): string {
        return "{$this->field} {$this->order}";
    }
}