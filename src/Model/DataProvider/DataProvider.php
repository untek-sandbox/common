<?php

namespace Untek\Model\DataProvider;

use Doctrine\Persistence\ObjectRepository;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\DataProvider\Dto\CollectionData;
use Untek\Model\DataProvider\Dto\PageResponse;
use Untek\Model\DataProvider\Exceptions\GreaterMaxPageException;
use Untek\Model\DataProvider\Helpers\DataProviderHelper;
use Untek\Model\DataProvider\Interfaces\ExpandQueryInterface;
use Untek\Model\DataProvider\Interfaces\FilterQueryInterface;
use Untek\Model\DataProvider\Interfaces\PageQueryInterface;
use Untek\Model\DataProvider\Interfaces\SortQueryInterface;

class DataProvider
{

    public function __construct(private ObjectRepository|RepositoryCountByInterface $repository)
    {
    }

    /**
     * @param object $query
     * @return CollectionData
     */
    public function findAll(object $query): CollectionData
    {
        $pageNumber = $query->getPage()->getNumber() ?? 1;
        $queryParameters = DataProviderHelper::extractParams($query);
        
        $collection = $this->repository->findBy(
            $queryParameters->getCriteria(), 
            $queryParameters->getOrderBy(), 
            $queryParameters->getLimit(), 
            $queryParameters->getOffset(), 
            $queryParameters->getExpand()
        );
        $count = $this->repository->countBy($queryParameters->getCriteria());

        $pageCount = DataProviderHelper::getPageCount($queryParameters->getLimit(), $count);

        /*if ($pageNumber > $pageCount) {
            $message = "This value should be less than or equal to {$pageCount}.";
            throw new GreaterMaxPageException($message);
        }*/

        $page = new PageResponse();
        $page->setPageSize($queryParameters->getLimit());
        $page->setPageNumber($queryParameters->getPageNumber());
        $page->setItemsTotalCount($count);
        $page->setPageCount($pageCount);

        return new CollectionData($collection, $page);
    }

    /**
     * @param int $id
     * @return object
     * @throws NotFoundException
     */
    public function findOneById(int $id): object
    {
        $entity = $this->repository->find($id);
        if (empty($entity)) {
            throw new NotFoundException('Entity not found!');
        }
        return $entity;
    }
}