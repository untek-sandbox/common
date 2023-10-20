<?php

namespace Untek\Database\Base\Domain\Repositories\Eloquent;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Base\Domain\Entities\TableEntity;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;

class SchemaRepository
{

    use EloquentTrait;

    private $dbRepository;

    public function __construct(Manager $capsule)
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
                $columnCollection = $this->dbRepository->allColumnsByTable($tableEntity->getName(), $tableEntity->getSchemaName());
                $tableEntity->setColumns($columnCollection);
                $relationCollection = $this->dbRepository->allRelations($tableEntity->getName());
                $tableEntity->setRelations($relationCollection);
                $newCollection->add($tableEntity);
            }
        }
        return $newCollection;
    }

    /**
     * @return Enumerable | TableEntity[]
     */
    public function allTables(): Enumerable
    {
        return $this->dbRepository->allTables();
    }
}
