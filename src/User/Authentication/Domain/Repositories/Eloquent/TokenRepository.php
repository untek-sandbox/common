<?php

namespace Untek\User\Authentication\Domain\Repositories\Eloquent;

use Untek\User\Authentication\Domain\Entities\TokenEntity;
use Untek\User\Authentication\Domain\Interfaces\Repositories\TokenRepositoryInterface;
use Untek\Model\Query\Entities\Query;
use Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository;

class TokenRepository extends BaseEloquentCrudRepository implements TokenRepositoryInterface
{

    public function tableName(): string
    {
        return 'user_token';
    }

    public function getEntityClass(): string
    {
        return TokenEntity::class;
    }

    public function findOneByValue(string $value, string $type): TokenEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'value' => $value,
            'type' => $type,
        ]);
        return $this->findOne($query);
    }
}
