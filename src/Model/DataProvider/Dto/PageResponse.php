<?php

namespace Untek\Model\DataProvider\Dto;

class PageResponse
{

    private int $pageNumber = 1;
    private int $pageSize;
    private int $pageCount;
    private int $itemsTotalCount;

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    public function getItemsTotalCount(): int
    {
        return $this->itemsTotalCount;
    }

    public function setItemsTotalCount(int $itemsTotalCount): void
    {
        $this->itemsTotalCount = $itemsTotalCount;
    }

}