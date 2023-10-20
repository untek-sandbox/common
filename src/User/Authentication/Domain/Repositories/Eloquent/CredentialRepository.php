<?php

namespace Untek\User\Authentication\Domain\Repositories\Eloquent;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Query\Entities\Query;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Enums\CredentialTypeEnum;
use Untek\User\Authentication\Domain\Interfaces\Repositories\CredentialRepositoryInterface;

class CredentialRepository extends \Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository implements CredentialRepositoryInterface
{

    public function tableName(): string
    {
        return 'user_credential';
    }

    public function getEntityClass(): string
    {
        return CredentialEntity::class;
    }

    public function allByIdentityId(int $identityId, array $types = null): Enumerable
    {
        $query = new Query;
        $query->whereByConditions([
            'identity_id' => $identityId,
            'type' => $types,
        ]);
        return $this->findAll($query);
    }

    public function findOneByCredential(string $credential, string|array $type = CredentialTypeEnum::LOGIN): CredentialEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'credential' => $credential,
            'type' => $type,
        ]);
        return $this->findOne($query);
    }

    public function findOneByCredentialValue(string $credential): CredentialEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'credential' => $credential,
        ]);
        return $this->findOne($query);
    }

    public function findOneByValidation(string $validation, string $type): CredentialEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'validation' => $validation,
            'type' => $type,
        ]);
        return $this->findOne($query);
    }
}
