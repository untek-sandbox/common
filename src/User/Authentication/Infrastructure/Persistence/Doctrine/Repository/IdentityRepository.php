<?php

namespace Untek\User\Authentication\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Untek\Component\Relation\Interfaces\RelationConfigInterface;
use Untek\Database\Base\Hydrator\HydratorInterface;
use Untek\Database\Doctrine\Domain\Base\AbstractDoctrineCrudRepository;
use Untek\User\Authentication\Application\Services\UserAssignedRolesRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Infrastructure\Persistence\Relation\IdentityRelation;
use Untek\User\Identity\Domain\Model\InMemoryUser;

class IdentityRepository extends AbstractDoctrineCrudRepository implements IdentityRepositoryInterface, ObjectRepository
{

    public function __construct(Connection $connection, private UserAssignedRolesRepositoryInterface $assignedRolesRepository)
    {
        parent::__construct($connection);
    }

    public function getTableName(): string
    {
        return 'user_identity';
    }

    public function getClassName()
    {
        return InMemoryUser::class;
    }

    public function getRelation(): RelationConfigInterface
    {
        return new IdentityRelation();
    }

    public function getUserById(int $id): UserInterface
    {
        $identity = $this->findOneById($id, ['assignments']);
        $roles = $this->getRolesById($id);
        $identity->setRoles($roles);
        return $identity;
    }

    protected function hydrate(array $item): object
    {
        return new InMemoryUser($item['id'], $item['username'], [], $item['status_id'] == 100);
    }

    protected function dehydrate(object $entity): array
    {
        /** @var InMemoryUser $entity */
        return [
            'username' => $entity->getUsername(),
            'status_id' => $entity->isEnabled() ? 100 : 0,
            'created_at' => (new \DateTimeImmutable())->format(\DateTimeImmutable::ISO8601),
        ];
    }

    private function getRolesById(int $id): array {
        $assignments = $this->assignedRolesRepository->findByUserId($id);
        $roles = [];
        foreach ($assignments as $assignment) {
            $roles[] = $assignment->getRole();
        }
        return $roles;
    }
}