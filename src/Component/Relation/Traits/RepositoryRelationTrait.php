<?php

namespace Untek\Component\Relation\Traits;

use Untek\Component\Relation\Libs\RelationLoader;

trait RepositoryRelationTrait
{

    public function relations(): array
    {
        return [];
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
