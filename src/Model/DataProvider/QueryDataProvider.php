<?php

namespace Untek\Model\DataProvider;

use Untek\Model\DataProvider\Dto\CollectionData;
use Untek\Model\DataProvider\Dto\PageResponse;
use Untek\Model\DataProvider\Helpers\DataProviderHelper;
use Untek\Model\DataProvider\Interfaces\DataProviderWithQueryInterface;

class QueryDataProvider
{

    public function __construct(private DataProviderWithQueryInterface $repository)
    {
    }

    /**
     * @param object $query
     * @return CollectionData
     */
    public function findAll(object $query): CollectionData
    {
        $queryParameters = DataProviderHelper::extractParams($query);
        $collection = $this->repository->findByQuery($query);
        $count = $this->repository->countByQuery($query);
        $pageCount = DataProviderHelper::getPageCount($queryParameters->getLimit(), $count);

        $page = new PageResponse();
        $page->setPageSize($queryParameters->getLimit());
        $page->setPageNumber($queryParameters->getPageNumber());
        $page->setItemsTotalCount($count);
        $page->setPageCount($pageCount);

        return new CollectionData($collection, $page);
    }
}