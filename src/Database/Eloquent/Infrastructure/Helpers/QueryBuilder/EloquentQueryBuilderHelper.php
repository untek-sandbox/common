<?php

namespace Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Untek\Database\Base\Domain\Helpers\DbHelper;
use Untek\Database\Base\Domain\Interfaces\QueryBuilderInterface;
use Untek\Model\Query\Entities\Join;
use Untek\Model\Query\Entities\Query;
use Untek\Model\Query\Entities\Where;
use Untek\Model\Query\Enums\OperatorEnum;

class EloquentQueryBuilderHelper implements QueryBuilderInterface
{

    public static function fillQueryBuilder(QueryBuilder $queryBuilder, array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): void
    {
        if ($orderBy) {
            self::setOrder($orderBy, $queryBuilder);
        }
        if ($offset) {
            $queryBuilder->offset($offset);
        }
        if ($limit) {
            $queryBuilder->limit($limit);
        }
        if ($criteria) {
            self::setWhere($criteria, $queryBuilder);
        }
    }

    public static function forgeColumnName(string $columnName, Builder $queryBuilder): string
    {
        if (strpos($columnName, '.') === false) {
            $columnName = $queryBuilder->from . '.' . $columnName;
        }
        return $columnName;
    }

    public static function setWhere(array $criteria, Builder $queryBuilder)
    {
        if (!empty($criteria)) {
            foreach ($criteria as $key => $value) {
                $column = self::forgeColumnName($key, $queryBuilder);
                if (is_array($value)) {
                    $queryBuilder->whereIn($column, $value);
                } else {
                    $queryBuilder->where($column, $value);
                }
            }
        }
    }

    public static function setJoin(Query $query, Builder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if (!empty($queryArr['join_new'])) {
            /** @var Join $join */
            foreach ($queryArr['join_new'] as $join) {
                $queryBuilder->join($join->table, $join->first, $join->operator, $join->second, $join->type, $join->where);
            }
        }
        if (!empty($queryArr[Query::JOIN])) {
            foreach ($queryArr[Query::JOIN] as $key => $value) {
                $queryBuilder->join($value['table'], $value['on'][0], '=', $value['on'][1], $value['type']);
            }
        }
    }

    public static function setOrder(array $order, Builder $queryBuilder)
    {
        if (!empty($order)) {
            foreach ($order as $field => $direction) {
                $column = self::forgeColumnName($field, $queryBuilder);
                $queryBuilder->orderBy($column, DbHelper::encodeDirection($direction));
            }
        }
    }

    public static function setGroupBy(Query $query, Builder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if (!empty($queryArr[Query::GROUP])) {
            $queryBuilder->groupBy($queryArr[Query::GROUP]);
            /*foreach ($queryArr[Query::GROUP] as $field => $direction) {
                $queryBuilder->groupBy($field, DbHelper::encodeDirection($direction));
            }*/
        }
    }

    public static function setSelect(Query $query, Builder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if (!empty($queryArr[Query::SELECT])) {
            $queryBuilder->select($queryArr[Query::SELECT]);
        }
    }

    public static function setPaginate(Query $query, Builder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if (!empty($queryArr[Query::LIMIT])) {
            $queryBuilder->limit($queryArr[Query::LIMIT]);
        }
        if (!empty($queryArr[Query::OFFSET])) {
            $queryBuilder->offset($queryArr[Query::OFFSET]);
        }

        if ($query->getLimit() !== null) {
            $queryBuilder->limit($query->getLimit());
        }
        if ($query->getOffset() !== null) {
            $queryBuilder->offset($query->getOffset());
        }
    }

}