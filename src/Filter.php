<?php

namespace Guym4c\GhostApiPhp;

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
     * @param Filter $filter
     * @return self
     */
    public function with(self $filter): self {
        $this->join($filter, '+');
        return $this;
    }

    /**
     * @param Filter $filter
     * @return self
     */
    public function else(self $filter): self {
        $this->join($filter, ',');
        return $this;
    }

    private function join (self $filter, string $type): void {
        $this->filters[] = [
            'filter' => $filter,
            'type' => $type,
        ];
    }

    public function __toString(): string {
        $result = '';
        foreach ($this->filters as $filter) {
            if (is_array($filter)) {
                $result .= "{$filter['type']}(" . (string)$filter['filter'] . ')';
            } else {
                $result .= $filter;
            }
        }
        return $result;
    }
}