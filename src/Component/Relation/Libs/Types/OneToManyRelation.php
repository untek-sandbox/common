<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Persistence\ObjectRepository;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Core\Collection\Helpers\CollectionHelper;

class OneToManyRelation extends BaseRelation implements RelationInterface
{

    /** Связующее поле */
    public string $relationAttribute;

    protected function loadRelation(array $collection): void
    {
        $ids = CollectionHelper::getColumn($collection, $this->relationAttribute);
        $ids = array_unique($ids);
        $foreignCollection = $this->loadRelationByIds($ids);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($collection as $entity) {
            $relationIndex = $propertyAccessor->getValue($entity, $this->relationAttribute);
            if (!empty($relationIndex)) {
                $relCollection = [];
                foreach ($foreignCollection as $foreignEntity) {
                    $foreignValue = $propertyAccessor->getValue($foreignEntity, $this->foreignAttribute);
                    if ($foreignValue == $relationIndex) {
                        $relCollection[] = $foreignEntity;
                    }
                }
                $value = $relCollection;
                $value = $this->getValueFromPath($value);
                $propertyAccessor->setValue($entity, $this->relationEntityAttribute, $value);
            }
        }
    }

    protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $criteria): array
    {
        // count($ids)
        $collection = $foreignRepositoryInstance->findBy($criteria, null, null, null, $this->relations);
        return $collection;
    }
}
