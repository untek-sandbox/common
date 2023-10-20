<?php

namespace Untek\Database\Migration\Infrastructure\Migration\Abstract;

use Illuminate\Database\Schema\Builder;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;
use Untek\Database\Base\Domain\Repositories\Eloquent\SchemaRepository;

abstract class BaseMigration
{

    use EloquentTrait;

//    protected $capsule;

//    protected $schemaRepository;

    public function __construct(Manager $capsule/*, SchemaRepository $schemaRepository*/)
    {
        $this->setCapsule($capsule);
//        $this->schemaRepository = $schemaRepository;
    }

    /*public function getCapsule(): Manager
    {
        return $this->schemaRepository->getCapsule();
//        return $this->capsule;
    }*/

    protected function runSqlQuery(Builder $schema, $sql)
    {
        $connection = $schema->getConnection();
        $connection->select($sql);
    }
}