<?php

namespace Untek\Component\Relation\Traits;

use Untek\Component\Relation\Interfaces\RelationConfigInterface;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Component\Relation\Libs\RelationLoader;
use Untek\Core\Contract\Common\Exceptions\NotImplementedMethodException;

trait RepositoryRelationTrait
{

    public function getRelation(): RelationConfigInterface
    {
        throw new NotImplementedMethodException('Need relation class.');
    }

    public function relations(): array
    {
        return $this->getRelation()->relations();
    }

    public function loadRelations(array $collection, array $with)
    {
        $relations = $this->relations();
        if (empty($relations)) {
            return;
        }
        $relationLoader = new RelationLoader();
        $relationLoader->setRelations($relations);
        $relationLoader->setRepository($this);
        $relationLoader->loadRelations($collection, $with);
    }
}
