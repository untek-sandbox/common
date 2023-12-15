<?php

namespace Untek\Component\Relation\Traits;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Query\Entities\Query;
use Untek\Component\Relation\Libs\RelationLoader;

trait RepositoryRelationTrait
{

    public function relations()
    {
        return [];
    }

    public function loadRelations(Enumerable|array $collection, array $with)
    {

//        if (method_exists($this, 'relations')) {
        $relations = $this->relations();
        if (empty($relations)) {
            return;
        }
        $relationLoader = new RelationLoader();
        $relationLoader->setRelations($relations);
        $relationLoader->setRepository($this);
        $relationLoader->loadRelations($collection, $with);
//        }
    }

    /*public function loadRelationsByQuery(Enumerable $collection, Query $query)
    {
        $this->loadRelations($collection, $query->getWith() ?: []);
    }*/

    /*public function loadRelations(Enumerable $collection, array $with)
    {
        $query = $this->forgeQuery();
        $query->with($with);
        $queryFilter = $this->queryFilterInstance($query);
        $queryFilter->loadRelations($collection);
    }*/
}
