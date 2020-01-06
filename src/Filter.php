<?php

namespace Guym4c\GhostApiPhp;

use Iterator;

class Filter {

    private $filters;

    private function parse(string $combinator, string $property, string $comparator, $value, bool $valueIsLiteral): string {
        $comparator = $comparator == '='
            ? ''
            : $comparator;

        $value = is_string($value) && !$valueIsLiteral
            ? "'{$value}'"
            : $value;

        return "{$combinator}{$property}:{$comparator}{$value}";
    }

    public function by(string $property, string $comparator, $value, bool $valueIsLiteral = false): self {
        $this->filters = [$this->parse('', $property, $comparator, $value, $valueIsLiteral)];
        return $this;
    }

    public function and(string $property, string $comparator, $value, bool $valueIsLiteral = false): self {
        $this->filters[] = $this->parse('+', $property, $comparator, $value, $valueIsLiteral);
        return $this;
    }

    public function or(string $property, string $comparator, $value, bool $valueIsLiteral = false): self {
        $this->filters[] = $this->parse(',', $property, $comparator, $value, $valueIsLiteral);
        return $this;
    }

    /**
     * @param self[] $filters
     * @return self
     */
    public function with(array $filters): self {
        $this->filters[] = $filters;
        return $this;
    }

    public function __toString(): string {
        $result = '';
        foreach ($this->filters as $filter) {
            if ($filter instanceof self) {
                $result .= (string)$filter;
            } else {
                $result .= $filter;
            }
        }
        return $result;
    }
}