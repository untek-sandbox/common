<?php

namespace Untek\Component\Relation\Interfaces;

use Untek\Core\Collection\Interfaces\Enumerable;

interface RelationInterface
{

    public function run(Enumerable $collection): void;

}
