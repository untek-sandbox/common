<?php

namespace Untek\User\Authentication\Domain\Repositories\InMemory;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Untek\Database\Memory\Abstract\AbstractMemoryCrudRepository;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Identity\Domain\Model\InMemoryUser;

//use Symfony\Component\Security\Core\User\InMemoryUser;

class IdentityRepository extends AbstractMemoryCrudRepository implements IdentityRepositoryInterface, ObjectRepository
{

    public function __construct(private array $users)
    {
    }

    public function getClassName()
    {
        return InMemoryUser::class;
    }

    protected function getItems(): array
    {
        $items = [];
        foreach ($this->users as $attributes) {
            $username = $attributes['username'] ?? true;
            $enabled = $attributes['enabled'] ?? true;
            $roles = $attributes['roles'] ?? [];
            $items[] = new InMemoryUser($attributes['id'], $username, $roles, $enabled);
        }
        return $items;
    }

    public function getUserById(int $id): UserInterface
    {
        foreach ($this->users as $attributes) {
            if ($attributes['id'] === $id) {
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

    /*public function find($id)
    {
        DeprecateHelper::hardThrow('find');
    }*/

    /*public function findAll()
    {
        DeprecateHelper::hardThrow('findAll');
        // TODO: Implement findAll() method.
    }*/

    /*public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
    {
        DeprecateHelper::hardThrow('findBy');
        // TODO: Implement findBy() method.
    }

    public function findOneBy(array $criteria)
    {
        DeprecateHelper::hardThrow('findOneBy');
        // TODO: Implement findOneBy() method.
    }*/
}
