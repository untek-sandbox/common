<?php

namespace Untek\Component\Relation\Libs\Types;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Component\Relation\Interfaces\RelationInterface;

class VoidRelation extends BaseRelation implements RelationInterface
{

    protected function loadRelation(Enumerable $collection): void
    {

    }
}
