<?php

namespace Untek\Kaz\Eds\Domain\Repositories\Eloquent;

use Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use Untek\Kaz\Eds\Domain\Entities\LogEntity;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\LogRepositoryInterface;

class LogRepository extends BaseEloquentCrudRepository implements LogRepositoryInterface
{

    public function tableName() : string
    {
        return 'eds_crl_log';
    }

    public function getEntityClass() : string
    {
        return LogEntity::class;
    }


}

