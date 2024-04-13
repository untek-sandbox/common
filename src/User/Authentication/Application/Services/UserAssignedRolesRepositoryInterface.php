<?php

namespace Untek\User\Authentication\Application\Services;

use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\User\Authentication\Domain\Model\UserAssignedRoles;

interface UserAssignedRolesRepositoryInterface extends
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface
{

    /**
     * @param int $id
     * @return UserAssignedRoles[]
     */
    public function findByUserId(int $id): array;
}