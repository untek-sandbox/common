<?php

namespace Untek\Database\Eloquent\Domain\Orm;

use Untek\Model\EntityManager\Interfaces\OrmInterface;
use Untek\Database\Eloquent\Domain\Capsule\Manager;

class EloquentOrm implements OrmInterface
{

    private $connection;

    public function __construct(Manager $connection)
    {
        $this->connection = $connection;
    }

    public function beginTransaction()
    {
        $this->connection->getDatabaseManager()->beginTransaction();
    }

    public function rollbackTransaction()
    {
        $this->connection->getDatabaseManager()->rollBack();
    }

    public function commitTransaction()
    {
        $this->connection->getDatabaseManager()->commit();
    }
}
