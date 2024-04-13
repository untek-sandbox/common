<?php

namespace Untek\Database\Base\Domain\Repositories\Base;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Model\Shared\Helpers\EntityHelper;
use Untek\Database\Base\Domain\Entities\ColumnEntity;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;

abstract class DbRepository
{

    use EloquentTrait;

//    private $capsule;

    public function __construct(Manager $capsule)
    {
        $this->setCapsule($capsule);
    }

    public function connectionName()
    {
        return 'default';
    }

    /*public function getConnection(): Connection
    {
        $connection = $this->capsule->getConnection($this->connectionName());
        return $connection;
    }*/

    /*protected function getSchema(): SchemaBuilder
    {
        $connection = $this->getConnection();
        $schema = $connection->getSchemaBuilder();
        return $schema;
    }*/

    /*public function getCapsule(): Manager
    {
        return $this->capsule;
    }*/

    /**
     * @param string $tableName
     * @param string $schemaName
     * @return Enumerable | ColumnEntity[]
     */
    public function allColumnsByTable(string $tableName, string $schemaName = 'public'): Enumerable
    {
        $schema = $this->getSchema();
        $columnList = $schema->getColumnListing($tableName);
        $columnCollection = new Collection();
        foreach ($columnList as $columnName) {
            $columnType = $schema->getColumnType($tableName, $columnName);
            $columnEntity = new ColumnEntity();
            $columnEntity->setName($columnName);
            $columnEntity->setType($columnType);
            $columnCollection->add($columnEntity);
        }
        return $columnCollection;
    }

}
