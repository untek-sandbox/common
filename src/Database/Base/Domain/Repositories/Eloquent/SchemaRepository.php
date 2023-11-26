<?php

namespace Untek\Database\Base\Domain\Repositories\Eloquent;

use Doctrine\DBAL\Connection;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Base\Domain\Entities\ColumnEntity;
use Untek\Database\Base\Domain\Entities\RelationEntity;
use Untek\Database\Base\Domain\Entities\TableEntity;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;

class SchemaRepository
{

    use EloquentTrait;

    private $dbRepository;

    public function __construct(private Connection $connection)
    {
    }

    /*public function __construct(Manager $capsule)
    {
        $this->setCapsule($capsule);
        $driver = $this->getConnection()->getDriverName();

        if ($driver == DbDriverEnum::SQLITE) {
            $this->dbRepository = new \Untek\Database\Base\Domain\Repositories\Sqlite\DbRepository($capsule);
        } elseif ($driver == DbDriverEnum::PGSQL) {
            $this->dbRepository = new \Untek\Database\Base\Domain\Repositories\Postgres\DbRepository($capsule);
        } else {
            $this->dbRepository = new \Untek\Database\Base\Domain\Repositories\Mysql\DbRepository($capsule);
        }
    }*/

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
    }

    public function getCapsule(): Manager
    {
        return $this->capsule;
    }*/

    public function allTablesByName(array $nameList): Enumerable
    {
        /** @var TableEntity[] $collection */
        $collection = $this->allTables();
        $newCollection = new Collection();
        foreach ($collection as $tableEntity) {
            if (in_array($tableEntity->getName(), $nameList)) {
                $columnCollection = $this->allColumnsByTable($tableEntity->getName(), $tableEntity->getSchemaName());
                $tableEntity->setColumns($columnCollection);
                $relationCollection = $this->allRelations($tableEntity->getName());
                $tableEntity->setRelations($relationCollection);
                $newCollection->add($tableEntity);
            }
        }
        return $newCollection;
    }

    protected function allRelations(string $tableName) {
        $foreignKeys = $this->connection->getSchemaManager()->listTableForeignKeys($tableName);
        
        $collection = new Collection();
        
        if($foreignKeys) {
            foreach ($foreignKeys as $key) {
                $relationEntity = new RelationEntity();
//                $relationEntity->setConstraintName($key->getName());
                $relationEntity->setTableName($tableName);
                $relationEntity->setColumnName($key->getLocalColumns()[0]);
                $relationEntity->setForeignTableName($key->getForeignTableName());
                $relationEntity->setForeignColumnName($key->getForeignColumns()[0]);
                $collection->add($relationEntity);
            }
        }
        
        return $collection;
    }

    protected function allColumnsByTable(string $tableName, string $schemaName = 'public'): Enumerable
    {
        $columnList = $this->connection->getSchemaManager()->listTableColumns($tableName);
        $columnCollection = new Collection();
        foreach ($columnList as $column) {
            $columnType = $column->getType()->getName();
            $columnEntity = new ColumnEntity();
            $columnEntity->setName($column->getName());
            $columnEntity->setType($columnType);
            $columnCollection->add($columnEntity);
        }
        return $columnCollection;
    }

    /**
     * @return TableEntity[]
     */
    public function allTables(): array
    {
        $collection = [];
        $tableNames = $this->connection->getSchemaManager()->listTableNames();
        foreach ($tableNames as $tableName) {
            $tableEntity = new TableEntity();
            $tableEntity->setName($tableName);
            $tableEntity->setSchemaName('public');
//            $tableEntity->setDbName($connection->getDatabaseName());
            $collection[] = $tableEntity;
        }
        return $collection;
    }
}
