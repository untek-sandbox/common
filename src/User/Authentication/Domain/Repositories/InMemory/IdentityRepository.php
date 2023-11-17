<?php

namespace Untek\User\Authentication\Domain\Repositories\InMemory;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class IdentityRepository implements IdentityRepositoryInterface
{

    public function __construct(private array $users)
    {
    }

    public function getUserById(int $id): UserInterface
    {
        foreach ($this->users as $attributes) {
            if($attributes['id'] === $id) {
                $username = $attributes['username'] ?? true;
                $password = null;
                $enabled = $attributes['enabled'] ?? true;
                $roles = $attributes['roles'] ?? [];


//                print_r($attributes);
//                exit;
                return new InMemoryUser($username, $password, $roles, $enabled);
            }
        }

        throw new UserNotFoundException();
//        print_r($this->users);
        // TODO: Implement getUserById() method.
    }
}
