<?php

namespace Untek\User\Authentication\Infrastructure\Persistence\Eloquent\Repository;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Doctrine\Domain\Base\AbstractDoctrineCrudRepository;
use Untek\Database\Eloquent\Infrastructure\Abstract\AbstractEloquentCrudRepository;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;

class UserCredentialRepository extends AbstractEloquentCrudRepository implements CredentialServiceInterface
{

    public function getTableName(): string
    {
        return 'user_credential';
    }

    public function getClassName(): string
    {
        return CredentialEntity::class;
    }

    public function findByUserId(int $userId, array $types = null): array
    {
        $criteria = [
            'user_id' => $userId,
        ];
        if($types) {
//            $criteria['type'] = $types;
        }
        return $this->findBy($criteria);
    }

    public function findByCredential(string $credential, array $types = null): array
    {
        $criteria = [
            'credential' => $credential,
        ];
        if($types) {
//            $criteria['type'] = $types;
        }
        return $this->findBy($criteria);
    }
}