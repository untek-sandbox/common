<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResultCollection
{

    /** @var array|GenerateResult[] */
    private array $items = [];

    public function __construct(array $items = [])
    {
        if($items) {
            foreach ($items as $item) {
                $this->add($item);
            }
        }
    }

    public function add(GenerateResult $result) {
        $this->items[] = $result;
    }

    /**
     * @return array|GenerateResult[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}