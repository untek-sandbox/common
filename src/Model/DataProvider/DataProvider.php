<?php

namespace Untek\Model\DataProvider;

use Doctrine\Persistence\ObjectRepository;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\DataProvider\Dto\CollectionData;
use Untek\Model\DataProvider\Dto\PageResponse;
use Untek\Model\DataProvider\Exceptions\GreaterMaxPageException;
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
     * @throws GreaterMaxPageException
     */
    public function findAll(object $query): CollectionData
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

        $offset = $this->calculateOffset($query);
        $collection = $this->repository->findBy($criteria, $orderBy, $limit, $offset, $expand);
        $count = $this->repository->countBy($criteria);

        $pageCount = $this->getPageCount($limit, $count);

        if ($pageNumber > $pageCount) {
            $message = "This value should be less than or equal to {$pageCount}.";
            throw new GreaterMaxPageException($message);
        }

        $page = new PageResponse();
        $page->setPageSize($limit);
        $page->setPageNumber($pageNumber);
        $page->setItemsTotalCount($count);
        $page->setPageCount($pageCount);

        return new CollectionData($collection, $page);
    }

    protected function getPageCount(int $pageSize, int $totalCount): int
    {
        $pageCount = intval(ceil($totalCount / $pageSize));
        if ($pageCount < 1) {
            $pageCount = 1;
        }
        return $pageCount;
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

    protected function calculateOffset(object $query): ?int
    {
        $limit = $query->getPage()->getSize();
        $pageNumber = $query->getPage()->getNumber();
        $offset = null;

        if (!$offset && $pageNumber) {
            $offset = $limit * ($pageNumber - 1);
        }
        return $offset;
    }
}