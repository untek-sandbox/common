<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Persistence\ObjectRepository;
use Psr\Container\ContainerInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Core\Collection\Libs\Collection;
use Untek\Model\Shared\Interfaces\FindAllInterface;
use Untek\Component\Relation\Interfaces\RelationInterface;

class ManyToManyRelation extends BaseRelation implements RelationInterface
{

    public $viaRepositoryClass;
    public $viaSourceAttribute;
    public $viaTargetAttribute;

    /*public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(array $collection): void
    {
        $this->loadRelation($collection);
        $collection = $this->prepareCollection($collection);
    }

    protected function prepareCollection(array $collection)
    {
        if ($this->prepareCollection) {
            call_user_func($this->prepareCollection, $collection);
        }
    }

    protected function loadRelationByIds(array $ids): array
    {
        $foreignRepositoryInstance = $this->getRepositoryInstance();
        $criteria = [
            $this->foreignAttribute => $ids
        ];
        return $this->loadCollection($foreignRepositoryInstance, $criteria);
    }*/

    protected function loadViaByIds(array $ids): array
    {
        $foreignRepositoryInstance = $this->getViaRepositoryInstance();
        $criteria = [
            $this->viaSourceAttribute => $ids
        ];
        return $this->loadCollection($foreignRepositoryInstance, $criteria);
    }

    /*protected function getRepositoryInstance(): FindAllInterface
    {
        return $this->container->get($this->foreignRepositoryClass);
    }*/

    protected function getViaRepositoryInstance(): FindAllInterface
    {
        return $this->container->get($this->viaRepositoryClass);
    }

    protected function loadRelation(array $collection): void
    {
        $ids = CollectionHelper::getColumn($collection, $this->relationAttribute);
        $ids = array_unique($ids);
        $viaCollection = $this->loadViaByIds($ids);
        $targetIds = CollectionHelper::getColumn($viaCollection, $this->viaTargetAttribute);
        $targetIds = array_unique($targetIds);
        $foreignCollection = $this->loadRelationByIds($targetIds);
        $foreignCollection = CollectionHelper::indexing($foreignCollection, 'id');
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $indexedCollection = CollectionHelper::indexing($collection, 'id');

        $result = [];
        foreach ($viaCollection as $viaEntity) {
            $targetRelationIndex = $propertyAccessor->getValue($viaEntity, $this->viaTargetAttribute);
            $sourceIndex = $propertyAccessor->getValue($viaEntity, $this->viaSourceAttribute);
            $sourceEntity = $indexedCollection[$sourceIndex];
            $targetRelationEntity = $foreignCollection[$targetRelationIndex];
            $result[$sourceIndex][] = $targetRelationEntity;
        }
        foreach ($collection as $entity) {
            $sourceIndex = $propertyAccessor->getValue($entity, 'id');
            if (isset($result[$sourceIndex])) {
                $value = $result[$sourceIndex];
                $value = $this->getValueFromPath($value);
                $propertyAccessor->setValue($entity, $this->relationEntityAttribute, new Collection($value));
            }
        }
    }

    protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $criteria): array
    {
        //count($ids)
        $collection = $foreignRepositoryInstance->findBy($criteria, null, null, null, $this->relations);
        return $collection;
    }
}
