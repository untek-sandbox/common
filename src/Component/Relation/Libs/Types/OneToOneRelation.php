<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Persistence\ObjectRepository;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class OneToOneRelation extends BaseRelation implements RelationInterface
{

    public function __construct(
        string $relationAttribute,
        string $relationEntityAttribute,
        string $foreignRepositoryClass,
        string $foreignAttribute = 'id'
    )
    {
        $this->relationAttribute = $relationAttribute;
        $this->relationEntityAttribute = $relationEntityAttribute;
        $this->foreignRepositoryClass = $foreignRepositoryClass;
        $this->foreignAttribute = $foreignAttribute;
    }

    protected function loadRelation(array $collection): void
    {
        $ids = CollectionHelper::getColumn($collection, $this->relationAttribute);
        $ids = array_unique($ids);

        $foreignCollection = $this->loadRelationByIds($ids);
        $foreignCollection = CollectionHelper::indexing($foreignCollection, $this->foreignAttribute);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($collection as $entity) {
            $relationIndex = $propertyAccessor->getValue($entity, $this->relationAttribute);
            if (!empty($relationIndex)) {
                try {
                    if (isset($foreignCollection[$relationIndex])) {
                        $value = $foreignCollection[$relationIndex];
                        if ($this->matchCondition($value)) {
                            $value = $this->getValueFromPath($value);
                            $propertyAccessor->setValue($entity, $this->relationEntityAttribute, $value);
                        }
                    }
                } catch (\Throwable $e) {
                }
            }
        }
    }

    protected function matchCondition($row): bool
    {
        if (empty($this->condition)) {
            return true;
        }
        foreach ($this->condition as $key => $value) {
            if (empty($row[$key])) {
                return false;
            }
            if ($row[$key] !== $this->condition[$key]) {
                return false;
            }
        }
        return true;
    }

    protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $criteria): array
    {
        // count($ids)
        $collection = $foreignRepositoryInstance->findBy($criteria, null, 1, null, $this->relations);
        return $collection;
    }
}
