<?php

namespace Untek\Component\Relation\Libs;

use Untek\Component\Relation\Libs\Types\BaseRelation;

class RelationConfigurator
{

    private $relations = [];

    public function add(BaseRelation $relation): void
    {
        if (empty($relation->name)) {
            $relation->name = $relation->relationEntityAttribute;
        }
        $this->relations[$relation->name] = $relation;
    }

    public function toArray(): array
    {
        return $this->relations;
    }

    public function isEmpty(): bool
    {
        return empty($this->relations);
    }
}