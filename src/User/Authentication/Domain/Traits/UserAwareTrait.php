<?php

namespace Untek\User\Authentication\Domain\Traits;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

trait UserAwareTrait
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage = null;

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): UserInterface
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ( ! $user instanceof UserInterface) {
            throw new AuthenticationException();
        }
        return $user;
    }

}