<?php

namespace Untek\User\Authentication\Domain\UserProviders;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\User\Authentication\Domain\Helpers\TokenHelper;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

class MockApiTokenUserProvider implements UserProviderInterface
{
    public function __construct(private TokenServiceInterface $tokenService)
    {
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass(string $class)
    {
        return true;
    }

    public function loadUserByIdentifier(string $token): UserInterface
    {
        $user = $this->tokenService->findUserByToken($token);
        return $user;
//        dd($user);
//        throw new UserNotFoundException();
    }
}
