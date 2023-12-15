<?php

namespace Untek\Database\Doctrine\Domain\Base;

//use Illuminate\Database\Query\Builder as QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Model\Shared\Interfaces\GetEntityClassInterface;
use Untek\Database\Base\Domain\Traits\TableNameTrait;

DeprecateHelper::hardThrow();

abstract class BaseDoctrineRepository implements GetEntityClassInterface
{

//    use TableNameTrait;

    protected $autoIncrement = 'id';
    private $capsule;

    public function __construct(Connection $capsule)
    {
        $this->capsule = $capsule;
    }

    abstract public function getTableName(): string;

    public function autoIncrement()
    {
        return $this->autoIncrement;
    }

    /*public function getCapsule(): Connection
    {
        return $this->capsule;
    }*/

    public function getConnection(): Connection
    {
        return $this->capsule;
        //$connection = $this->capsule->getConnection();
        //return $connection;
    }

    public function encodeTableName(string $sourceTableName): string
    {
        return $sourceTableName;
        /*$tableAlias = $this->getCapsule()->getAlias();
        $targetTableName = $tableAlias->encode($this->connectionName(), $sourceTableName);
        return $targetTableName;*/
    }

    protected function tableNameForQuery(): string
    {
        return 'c';
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        $connection = $this->getConnection();
        $queryBuilder = $connection->createQueryBuilder()
            ->from($this->getTableName(), $this->tableNameForQuery())
            ->select('*');
        //$queryBuilder = $connection->table($this->tableNameAlias(), null, $this->connectionName());
        return $queryBuilder;
    }

    protected function getSchema(string $connectionName = null): AbstractSchemaManager
    {
        $connection = $this->getConnection($connectionName);
        $schema = $connection->getSchemaManager();
        return $schema;
    }

    protected function allByBuilder(QueryBuilder $queryBuilder)
    {
        $connection = $this->getConnection();
        $array = $connection->fetchAll($queryBuilder->getSQL());

        $entityClass = $this->getEntityClass();
        return CollectionHelper::create($entityClass, $array);
    }

    protected function countByBuilder(QueryBuilder $queryBuilder): int
    {
        $connection = $this->getConnection();
        $queryBuilder->select('COUNT(*) as count');
        return $connection->executeQuery($queryBuilder->getSQL())->fetchColumn(0);
    }

    protected function executeQuery(QueryBuilder $queryBuilder): PDOStatement {
        $connection = $this->getConnection();
        return $connection->executeQuery($queryBuilder->getSQL());
    }

    /*public function getEntityClass(): string
    {
        return $this->entityClass;
    }*/

    /*protected function oneByBuilder(QueryBuilder $queryBuilder)
    {
        $item = $queryBuilder->first();
        if (empty($item)) {
            throw new NotFoundException('Not found entity!');
        }
        return $this->forgeEntity($item);
    }*/

}
