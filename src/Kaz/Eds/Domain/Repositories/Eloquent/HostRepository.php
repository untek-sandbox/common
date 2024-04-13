<?php

namespace Untek\Kaz\Eds\Domain\Repositories\Eloquent;

use Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use Untek\Kaz\Eds\Domain\Entities\HostEntity;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\HostRepositoryInterface;

class HostRepository extends BaseEloquentCrudRepository implements HostRepositoryInterface
{

    public function tableName() : string
    {
        return 'eds_host';
    }

    public function getEntityClass() : string
    {
        return HostEntity::class;
    }


}

