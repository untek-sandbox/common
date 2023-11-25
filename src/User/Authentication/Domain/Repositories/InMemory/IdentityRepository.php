<?php

namespace Untek\User\Authentication\Domain\Repositories\InMemory;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
//use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Identity\Domain\Model\InMemoryUser;

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
                $enabled = $attributes['enabled'] ?? true;
                $roles = $attributes['roles'] ?? [];


//                print_r($attributes);
//                exit;
                return new InMemoryUser($attributes['id'], $username, $roles, $enabled);
            }
        }

        throw new UserNotFoundException();
//        print_r($this->users);
        // TODO: Implement getUserById() method.
    }
}
