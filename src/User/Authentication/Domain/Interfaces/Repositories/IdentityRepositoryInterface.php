<?php

namespace Untek\User\Authentication\Domain\Interfaces\Repositories;

use Symfony\Component\Security\Core\User\UserInterface;

interface IdentityRepositoryInterface
{

    public function getUserById(int $id): UserInterface;
}