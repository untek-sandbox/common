<?php

namespace Untek\Utility\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\Application\Interfaces\ResultInterface;

class GenerateResultCollection
{

    /** @var array|ResultInterface[] */
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

    public function add(ResultInterface $result): self
    {
        $this->items[] = $result;
        return $this;
    }

    /**
     * @return array|ResultInterface[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}