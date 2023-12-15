<?php

namespace Untek\Component\Relation\Libs\Types;

use Untek\Component\Relation\Interfaces\RelationInterface;

class VoidRelation extends BaseRelation implements RelationInterface
{

    protected function loadRelation(array $collection): void
    {

    }
}
