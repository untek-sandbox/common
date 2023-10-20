<?php

namespace Untek\Model\DataProvider;

use Doctrine\Persistence\ObjectRepository;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\DataProvider\Dto\CollectionData;
use Untek\Model\DataProvider\Dto\PageResponse;
use Untek\Model\DataProvider\Exceptions\GreaterMaxPageException;
use Forecast\Map\Shared\Infrastructure\Http\RestApi\GetListQueryInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;

class DataProvider
{

    public function __construct(private ObjectRepository|RepositoryCountByInterface $repository)
    {
    }

    /**
     * @param GetListQueryInterface $query
     * @return CollectionData
     * @throws GreaterMaxPageException
     */
    public function findAll(GetListQueryInterface $query): CollectionData
    {
        $criteria = $query->getFilter();
        $orderBy = $query->getSort();
        $limit = $query->getPage()->getSize();
        $pageNumber = $query->getPage()->getNumber();

        $offset = $this->calculateOffset($query);
        $collection = $this->repository->findBy($criteria, $orderBy, $limit, $offset);
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

    protected function calculateOffset(GetListQueryInterface $query): ?int
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