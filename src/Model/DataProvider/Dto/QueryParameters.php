<?php

namespace Untek\Model\DataProvider\Dto;

class QueryParameters
{

    public function __construct(
        private array $criteria,
        private ?array $orderBy = null,
        private ?int $limit = null,
        private ?int $offset = null,
        private ?array $expand = null,
        private int $pageNumber = 1,
    )
    {
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function getOrderBy(): ?array
    {
        return $this->orderBy;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getExpand(): ?array
    {
        return $this->expand;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
}