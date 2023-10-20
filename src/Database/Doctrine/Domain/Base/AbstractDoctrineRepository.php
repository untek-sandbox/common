<?php

namespace Untek\Database\Doctrine\Domain\Base;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Database\Doctrine\Domain\Helpers\QueryBuilder\DoctrineQueryBuilderHelper;

abstract class AbstractDoctrineRepository implements ObjectRepository
{
    private Connection $connection;

    abstract public function getTableName(): string;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    protected function tableNameForQuery(): string
    {
        return 'c';
    }

    /**
     * @param mixed $id
     * @return object|null
     * @throws Exception
     */
    public function find(mixed $id): ?object
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @return object[]
     * @throws Exception
     */
    public function findAll(): array
    {
        return $this->findBy([]);
    }

    /**
     * @param array $criteria
     * @return object|null
     * @throws Exception
     */
    public function findOneBy(array $criteria): ?object
    {
        $collection = $this->findBy($criteria, null, 1);
        return $collection[0] ?? null;
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws Exception
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $queryBuilder = $this->makeFindQueryBuilder($criteria, $orderBy, $limit, $offset);
        return $this->executeFindQuery($queryBuilder);
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        $connection = $this->getConnection();
        return $connection
            ->createQueryBuilder()
            ->from($this->getTableName(), $this->tableNameForQuery());
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return array
     * @throws Exception
     */
    protected function executeFindQuery(QueryBuilder $queryBuilder): array
    {
        $array = $this->getConnection()->fetchAllAssociative($queryBuilder->getSQL());
        return CollectionHelper::createEntityArray($this->getClassName(), $array);
    }

    private function makeFindQueryBuilder(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('*');
        DoctrineQueryBuilderHelper::fillQueryBuilder($queryBuilder, $criteria, $orderBy, $limit, $offset);
        return $queryBuilder;
    }
}