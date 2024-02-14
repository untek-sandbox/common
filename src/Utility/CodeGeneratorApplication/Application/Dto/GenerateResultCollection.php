<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResultCollection
{

    private array $items = [];

    public function add(GenerateResult $result) {
        $this->items[] = $result;
    }

    public function getAll(): array
    {
        return $this->items;
    }
}