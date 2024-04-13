<?php

/**
 * @var string $namespace
 * @var string $className
 */

?>

namespace <?= $namespace ?>;

use Untek\Component\Relation\Interfaces\RelationConfigInterface;
use Untek\Component\Relation\Libs\RelationConfigurator;

class <?= $className ?> implements RelationConfigInterface
{

    public function relations(RelationConfigurator $configurator): void
    {

    }
}
