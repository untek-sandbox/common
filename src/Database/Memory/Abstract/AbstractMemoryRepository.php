<?php

namespace Untek\Database\Memory\Abstract;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Persistence\ObjectRepository;
use Untek\Component\Relation\Traits\RepositoryRelationTrait;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Base\Hydrator\DefaultHydrator;
use Untek\Database\Base\Hydrator\HydratorInterface;

abstract class AbstractMemoryRepository implements ObjectRepository
{

    use RepositoryRelationTrait;

    protected HydratorInterface $mapper;

    abstract protected function getItems(): array;

    protected function getHydrator(): HydratorInterface
    {
        if (isset($this->mapper)) {
            return $this->mapper;
        }
        return new DefaultHydrator($this->getClassName());
    }

    protected function dehydrate(object $entity): array
    {
        $data = $this->getHydrator()->dehydrate($entity);
        return $data;
    }

    protected function hydrate(array $item): object
    {
        $entity = $this->getHydrator()->hydrate($item);
        return $entity;
    }

    public function find($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findOneBy(array $criteria)
    {
        $collection = $this->findBy($criteria, null, 1);
        return $collection[0] ?? null;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $relations = null): array
    {
        $criteriaMatching = $this->createCriteria($criteria, $orderBy, $limit, $offset);
        $collection = new Collection($this->getItems());
        $collection = $collection->matching($criteriaMatching);
        $list = $collection->toArray();
        if($relations) {
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