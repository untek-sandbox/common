<?php

namespace Untek\User\Authentication\Infrastructure\Persistence\Eloquent\Repository;

use Untek\Database\Doctrine\Domain\Base\AbstractDoctrineCrudRepository;
use Untek\Database\Eloquent\Infrastructure\Abstract\AbstractEloquentCrudRepository;
use Untek\User\Authentication\Application\Services\UserAssignedRolesRepositoryInterface;
use Untek\User\Authentication\Domain\Model\UserAssignedRoles;

class UserAssignedRolesRepository extends AbstractEloquentCrudRepository implements UserAssignedRolesRepositoryInterface
{

    public function getTableName(): string
    {
        return 'user_assigned_roles';
    }

    public function getClassName(): string
    {
        return UserAssignedRoles::class;
    }

    public function findByUserId(int $id): array {
        return $this->findBy(['user_id' => $id]);
    }
}