<?php

namespace Untek\Utility\CodeGenerator\Application\Dto;

class GenerateResultCollection
{

    /** @var array|FileResult[] */
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

    public function addResult(
        string $fileName = null,
        ?string $code = null,
        string $type = 'file',
    )
    {
        $this->add(new FileResult($fileName, $code, $type));
    }

    public function add(FileResult $result): self
    {
        $this->items[] = $result;
        return $this;
    }

    /**
     * @return array|FileResult[]
     */
    public function getAll(): array
    {
        return $this->items;
    }
}