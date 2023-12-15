<?php

namespace Untek\Component\Relation\Libs;

use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Instance\Helpers\InstanceHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Model\Query\Entities\Query;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Model\Repository\Interfaces\RelationConfigInterface;
use Untek\Model\Repository\Interfaces\RepositoryInterface;

class RelationLoader
{

    private $repository;
    private $relations;

    public function getRepository(): ObjectRepository
    {
        return $this->repository;
    }

    public function setRepository(ObjectRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function setRelations(array $relations): void
    {
        $this->relations = $relations;
    }

    private function getRelationTree($with): array
    {
        $relationTree = [];
        foreach ($with as $attribute => $withItem) {
            $relParts = null;
            if (is_string($withItem)) {
                $relParts1 = explode('.', $withItem);
                $attribute = $relParts1[0];
                unset($relParts1[0]);
                $relParts1 = array_values($relParts1);
                if ($relParts1) {
                    $relParts = [implode('.', $relParts1)];
                }
            } elseif (is_array($withItem)) {
                $relParts = $withItem;
            } elseif (is_object($withItem) && $withItem instanceof Query) {
                $relParts = $withItem->getParam(Query::WITH);
            }

            if (!empty($relParts)) {
                foreach ($relParts as $relPart) {
                    $relationTree[$attribute][] = $relPart;
                }
            } else {
                $relationTree[$attribute] = [];
            }
        }
        return $relationTree;
    }

    public function loadRelations(Enumerable|array $collection, array $with = [])
    {
        $relations = $this->relations;
        $relations = $this->prepareRelations($relations);
        $relations = ArrayHelper::index($relations, 'name');

        if ($with) {
            $relationTree = $this->getRelationTree($with);

            foreach ($relationTree as $attribute => $relParts) {
                if (empty($relations[$attribute])) {
                    throw new InvalidArgumentException('Relation "' . $attribute . '" not defined in repository "' . get_class($this->repository) . '"!');
                }
                /** @var RelationInterface $relation */
                $relation = $relations[$attribute];
                $relation = $this->ensureRelation($relation);

                if (is_object($relation)) {
                    if ($relParts) {
                        $relation->query = $relation->query ?: new Query();
                        $relation->query->with($relParts);
                    }
                    $relation->run($collection);
                }
            }
        }
    }

    private function prepareRelations(array $relations)
    {
        foreach ($relations as &$relation) {
            if (empty($relation['name'])) {
                $relation['name'] = $relation['relationEntityAttribute'];
            }
        }
        return $relations;
    }

    private function ensureRelation($relation): RelationInterface
    {
        if ($relation instanceof RelationInterface) {

        } elseif (is_array($relation) || is_string($relation)) {
//            $relation = ClassHelper::createObject($relation);
            $class = $relation['class'];
            $relationObject = new $class(ContainerHelper::getContainer());
            unset($relation['class']);
            PropertyHelper::setAttributes($relationObject, $relation);
            $relation = $relationObject;
        } else {
            throw new InvalidArgumentException('Definition of relation not correct!');
        }
        return $relation;
    }
}
