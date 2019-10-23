<?php

namespace Guym4c\GhostApiPhp\Model;

abstract class AbstractModel {

    protected function parsePropertyName(string $property) {
        return strtolower(
            preg_replace('/([A-Z])/', '_$1', $property));
    }

    protected function hydrate(array $json): void {
        foreach (get_object_vars($this) as $property => $value) {

            if (empty($value)) {
                $this->{$property} = $json[$this->parsePropertyName($property)] ?? null;
            }
        }
    }

    protected function populateArrayType(string $class, string $key, array $json): void {
        foreach ($json[$this->parsePropertyName($key)] as $value) {
            $this->{$key}[] = new $class($value);
        }
    }
}