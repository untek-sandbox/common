<?php

namespace Untek\Model\DataProvider\Dto;

class CollectionData
{

    private array $collection;
    private PageResponse $page;

    public function __construct(array $collection, PageResponse $page)
    {
        $this->collection = $collection;
        $this->page = $page;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function setCollection(array $collection): void
    {
        $this->collection = $collection;
    }

    public function getPage(): PageResponse
    {
        return $this->page;
    }

    public function setPage(PageResponse $page): void
    {
        $this->page = $page;
    }
}