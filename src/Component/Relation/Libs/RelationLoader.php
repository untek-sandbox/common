<?php

namespace Untek\Component\Relation\Libs;

use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Component\Relation\Libs\Types\BaseRelation;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Repository\Interfaces\RelationConfigInterface;

class RelationLoader
{

    private $repository;
    private RelationConfigurator $relations;

    public function getRepository(): ObjectRepository
    {
        return $this->repository;
    }

    public function setRepository(ObjectRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function setRelations(RelationConfigurator $relations): void
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

    public function loadRelations(array $collection, array $with = [])
    {
        $relations = $this->relations->toArray();
        if ($with) {
            $relationTree = $this->getRelationTree($with);

            foreach ($relationTree as $attribute => $relParts) {
                if (empty($relations[$attribute])) {
                    throw new InvalidArgumentException('Relation "' . $attribute . '" not defined in repository "' . get_class($this->repository) . '"!');
                }
                /** @var RelationInterface $relation */
                $relation = $relations[$attribute];
                $relation->setContainer(ContainerHelper::getContainer());

                if (is_object($relation)) {
                    if ($relParts) {
                        $relation->relations = $relParts;
                    }
                    $relation->run($collection);
                }
            }
        }
    }
}
