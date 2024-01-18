<?php

namespace Untek\User\Authentication\Infrastructure\Persistence\Doctrine\Repository;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Doctrine\Domain\Base\AbstractDoctrineCrudRepository;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;

class UserCredentialRepository extends AbstractDoctrineCrudRepository implements CredentialServiceInterface
{

    public function getTableName(): string
    {
        return 'user_credential';
    }

    public function getClassName(): string
    {
        return CredentialEntity::class;
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

    protected function hydrate(array $item): object
    {
        $item['identity_id'] = $item['user_id'];
        return parent::hydrate($item);
    }
}