<?php

namespace Untek\Kaz\Eds\Domain\Repositories\Eloquent;

use Untek\Database\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use Untek\Kaz\Eds\Domain\Entities\CertificateEntity;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\CertificateRepositoryInterface;

class CertificateRepository extends BaseEloquentCrudRepository implements CertificateRepositoryInterface
{

    public function tableName() : string
    {
        return 'eds_certificate';
    }

    public function getEntityClass() : string
    {
        return CertificateEntity::class;
    }


}

