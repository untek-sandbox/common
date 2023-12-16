<?php

namespace Untek\Component\Relation\Interfaces;

use Untek\Component\Relation\Libs\RelationConfigurator;

interface RelationConfigInterface
{

    public function relations(RelationConfigurator $configurator): void;
}
