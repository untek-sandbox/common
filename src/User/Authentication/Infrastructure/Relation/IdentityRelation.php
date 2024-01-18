<?php

namespace Untek\User\Authentication\Infrastructure\Relation;

use Untek\Component\Relation\Interfaces\RelationConfigInterface;
use Untek\Component\Relation\Libs\RelationConfigurator;
use Untek\Component\Relation\Libs\Types\OneToManyRelation;
use Untek\User\Authentication\Application\Services\UserAssignedRolesRepositoryInterface;

class IdentityRelation implements RelationConfigInterface
{

    public function relations(RelationConfigurator $configurator): void
    {
        $configurator->add(
            new OneToManyRelation(
                'id',
                'assignments',
                UserAssignedRolesRepositoryInterface::class,
                'user_id',
            )
        );
    }
}