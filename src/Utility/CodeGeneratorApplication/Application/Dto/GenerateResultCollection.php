<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResultCollection
{

    /** @var array|GenerateResult[] */
    private array $items = [];

    public function __construct(array $items = [])
    {
        $this->fill($items);
    }

    public function merge(GenerateResultCollection $collection): self
    {
        $this->fill($collection->getAll());
        return $this;
    }

    protected function fill(array $items): self
    {
        if (empty($items)) {
            return $this;
        }
        foreach ($items as $item) {
            $this->add($item);
        }
        return $this;
    }

    public function add(GenerateResult $result): self
    {
        $this->items[] = $result;
        return $this;
    }

    /**
     * @return array|GenerateResult[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}