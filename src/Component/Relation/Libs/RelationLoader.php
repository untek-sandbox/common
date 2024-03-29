<?php

namespace Untek\Component\Relation\Libs;

use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Model\Repository\Interfaces\RelationConfigInterface;

class RelationLoader
{

    public function __construct(
        private ?ContainerInterface $container = null,
    )
    {
    }

    private function getContainer(): ContainerInterface
    {
        return $this->container ?: ContainerHelper::getContainer();
    }

    public function load(ObjectRepository $repository, array $collection, array $with = []): void
    {
        $relationDefinition = $repository->relations();
        $relations = $relationDefinition->toArray();
        if ($with) {
            $relationTree = $this->getRelationTree($with);
            foreach ($relationTree as $attribute => $relParts) {
                if (empty($relations[$attribute])) {
                    throw new InvalidArgumentException('Relation "' . $attribute . '" not defined in repository "' . get_class($repository) . '"!');
                }
                /** @var RelationInterface $relation */
                $relation = $relations[$attribute];
                $relation->setContainer($this->getContainer());
                if (is_object($relation)) {
                    if ($relParts) {
                        $relation->relations = $relParts;
                    }
                    $relation->run($collection);
                }
            }
        }
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
}
