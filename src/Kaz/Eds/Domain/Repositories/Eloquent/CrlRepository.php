<?php

namespace Untek\Kaz\Eds\Domain\Repositories\Eloquent;

use Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use Untek\Kaz\Eds\Domain\Entities\CrlEntity;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\CrlRepositoryInterface;

class CrlRepository extends BaseEloquentCrudRepository implements CrlRepositoryInterface
{

    public function tableName() : string
    {
        return 'eds_crl';
    }

    public function getEntityClass() : string
    {
        return CrlEntity::class;
    }


}
