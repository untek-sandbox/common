<?php

namespace Untek\Model\DataProvider\Helpers;

use Untek\Model\DataProvider\Dto\QueryParameters;
use Untek\Model\DataProvider\Interfaces\ExpandQueryInterface;
use Untek\Model\DataProvider\Interfaces\FilterQueryInterface;
use Untek\Model\DataProvider\Interfaces\PageQueryInterface;
use Untek\Model\DataProvider\Interfaces\SortQueryInterface;

class DataProviderHelper
{

    public static function getPageCount(int $pageSize, int $totalCount): int
    {
        $pageCount = intval(ceil($totalCount / $pageSize));
        if ($pageCount < 1) {
            $pageCount = 1;
        }
        return $pageCount;
    }

    public static function calculateOffset(object $query): ?int
    {
        $limit = $query->getPage()->getSize();
        $pageNumber = $query->getPage()->getNumber();
        $offset = null;

        if (!$offset && $pageNumber) {
            $offset = $limit * ($pageNumber - 1);
        }
        return $offset;
    }

    public static function extractParams(object $query): QueryParameters
    {
        if($query instanceof SortQueryInterface) {
            $orderBy = $query->getSort();
        } else {
            $orderBy = [];
        }

        if($query instanceof FilterQueryInterface) {
            $criteria = $query->getFilter();
        } else {
            $criteria = [];
        }

        if($query instanceof PageQueryInterface) {
            $limit = $query->getPage()->getSize();
            $pageNumber = $query->getPage()->getNumber();
        } else {
            $limit = null;
            $pageNumber = 1;
        }

        if($query instanceof ExpandQueryInterface) {
            $expand = $query->getExpand();
        } else {
            $expand = null;
        }

        $offset = self::calculateOffset($query);
        
        return new QueryParameters($criteria, $orderBy, $limit, $offset, $expand, $pageNumber);
    }
}