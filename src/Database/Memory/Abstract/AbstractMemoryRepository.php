<?php

namespace Untek\Database\Memory\Abstract;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Persistence\ObjectRepository;
use Untek\Component\Relation\Traits\RepositoryRelationTrait;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Base\Hydrator\Traits\HydratorTrait;

abstract class AbstractMemoryRepository implements ObjectRepository
{

    use RepositoryRelationTrait;
    use HydratorTrait;

    abstract protected function getItems(): array;

    public function find($id, ?array $relations = null)
    {
        return $this->findOneBy(['id' => $id], $relations);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findOneBy(array $criteria, ?array $relations = null)
    {
        $collection = $this->findBy($criteria, null, 1, null, $relations);
        return $collection[0] ?? null;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $relations = null): array
    {
        $criteriaMatching = $this->createCriteria($criteria, $orderBy, $limit, $offset);
        $collection = new Collection($this->getItems());
        $collection = $collection->matching($criteriaMatching);
        $list = $collection->toArray();
        if ($relations) {
            $this->loadRelations($list, $relations);
        }
        return $list;
    }

    protected function createCriteria(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): Criteria
    {
        $criteriaMatching = new Criteria();
        if ($orderBy) {
            $criteriaMatching->orderBy($orderBy);
        }
        if ($offset) {
            $criteriaMatching->setFirstResult($offset);
        }
        if ($limit) {
            $criteriaMatching->setMaxResults($limit);
        }
        if ($criteria) {
            foreach ($criteria as $column => $value) {
                if (is_array($value)) {
                    $expr = new Comparison($column, Comparison::IN, $value);
                } else {
                    $expr = new Comparison($column, Comparison::EQ, $value);
                }
                $criteriaMatching->andWhere($expr);
            }
        }
        return $criteriaMatching;
    }
}