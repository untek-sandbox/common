<?php

/**
 * @var string $namespace
 * @var string $className
 * @var PropertyGenerator[] $properties
 */

use Laminas\Code\Generator\PropertyGenerator;

?>

namespace <?= $namespace ?>;

use Untek\Model\DataProvider\Dto\PageRequest;
use Untek\Model\DataProvider\Interfaces\ExpandQueryInterface;
use Untek\Model\DataProvider\Interfaces\FilterQueryInterface;
use Untek\Model\DataProvider\Interfaces\PageQueryInterface;
use Untek\Model\DataProvider\Interfaces\SortQueryInterface;

class <?= $className ?> implements
    FilterQueryInterface,
    SortQueryInterface,
    ExpandQueryInterface,
    PageQueryInterface
{

    private array $filter = [];
    private array $sort = [];
    private array $expand = [];
    private PageRequest $page;

    public function getFilter(): array
    {
        return $this->filter;
    }

    public function setFilter(array $filter): void
    {
        $this->filter = $filter;
    }

    public function getSort(): array
    {
        return $this->sort;
    }

    public function setSort(array $sort): void
    {
        $this->sort = $sort;
    }

    public function getExpand(): array
    {
        return $this->expand;
    }

    public function setExpand(array $expand): void
    {
        $this->expand = $expand;
    }

    public function getPage(): PageRequest
    {
        return $this->page;
    }

    public function setPage(PageRequest $page): void
    {
        $this->page = $page;
    }
}