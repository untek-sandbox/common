<?php

namespace Untek\User\Authentication\Domain\Interfaces\Services;

use Symfony\Component\Security\Core\User\UserInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;

interface TokenServiceInterface
{

    public function getTokenByIdentity(UserInterface $identityEntity): TokenValueEntity;

    /**
     * @param string $token
     * @return int
     *
     * @throws NotFoundException
     */
    public function getIdentityIdByToken(string $token): int;
}
