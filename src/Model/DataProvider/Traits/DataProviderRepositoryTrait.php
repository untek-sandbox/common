<?php

namespace Untek\Model\DataProvider\Traits;

use Illuminate\Database\Query\Builder;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;
use Untek\Model\DataProvider\Helpers\DataProviderHelper;
use Untek\Model\DataProvider\Interfaces\ExpandQueryInterface;
use Untek\Model\DataProvider\Interfaces\FilterQueryInterface;
use Untek\Model\DataProvider\Interfaces\PageQueryInterface;
use Untek\Model\DataProvider\Interfaces\SortQueryInterface;

trait DataProviderRepositoryTrait
{

    protected function addConditionInQueryBuilder(object $query, Builder $queryBuilder): void
    {
        if($query instanceof FilterQueryInterface) {
            $criteria = $query->getFilter();
        } else {
            $criteria = [];
        }
        EloquentQueryBuilderHelper::setWhere($criteria, $queryBuilder);
    }

    public function countByQuery(object $query): int
    {
        $queryBuilder = $this->createQueryBuilderByQuery($query);
        $queryBuilder->select('COUNT(*) as count');
        $this->addConditionInQueryBuilder($query, $queryBuilder);
        return $queryBuilder->count();
    }

    public function findByQuery(object $query): array
    {
        $queryBuilder = $this->createQueryBuilderByQuery($query);
        $this->addConditionInQueryBuilder($query, $queryBuilder);
        $list = $this->executeFindQuery($queryBuilder);
        if ($query instanceof ExpandQueryInterface) {
            $expand = $query->getExpand();
        } else {
            $expand = null;
        }
        if ($expand) {
            $this->loadRelations($list, $expand);
        }
        return $list;
    }

    protected function createQueryBuilderByQuery(object $query): Builder
    {
        if ($query instanceof SortQueryInterface) {
            $orderBy = $query->getSort();
        } else {
            $orderBy = [];
        }
        if ($query instanceof PageQueryInterface) {
            $limit = $query->getPage()->getSize();
        } else {
            $limit = null;
        }
        if($query instanceof ExpandQueryInterface) {
            $expand = $query->getExpand();
        } else {
            $expand = null;
        }
        $offset = DataProviderHelper::calculateOffset($query);
        return $this->makeFindQueryBuilder([], $orderBy, $limit, $offset);
    }
}
