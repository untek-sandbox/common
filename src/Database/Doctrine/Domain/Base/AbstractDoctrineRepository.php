<?php

namespace Untek\Database\Doctrine\Domain\Base;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Untek\Database\Base\Hydrator\DefaultMapper;
use Untek\Database\Base\Hydrator\HydratorInterface;
use Untek\Database\Doctrine\Domain\Helpers\QueryBuilder\DoctrineQueryBuilderHelper;

abstract class AbstractDoctrineRepository implements ObjectRepository
{
    private Connection $connection;
    protected HydratorInterface $mapper;

    abstract public function getTableName(): string;

    public function __construct(Connection $connection, HydratorInterface $mapper = null)
    {
        $this->connection = $connection;
        if ($mapper != null) {
            $this->mapper = $mapper;
        }
    }

    protected function getMapper(): HydratorInterface
    {
        if (isset($this->mapper)) {
            return $this->mapper;
        }
        return new DefaultMapper($this->getClassName());
    }

    protected function serializeEntity(object $entity): array
    {
        $data = $this->getMapper()->dehydrate($entity);
        return $data;
    }

    protected function restoreEntity(array $item): object
    {
        $entity = $this->getMapper()->hydrate($item);
        return $entity;
    }

    protected function getConnection(): Connection
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

    protected function createQueryBuilder(): QueryBuilder
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
        $data = $this->getConnection()->fetchAllAssociative($queryBuilder->getSQL());
        foreach ($data as $key => $item) {
            $data[$key] = $this->restoreEntity($item);
        }
        return $data;
    }

    private function makeFindQueryBuilder(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select('*');
        DoctrineQueryBuilderHelper::fillQueryBuilder($queryBuilder, $criteria, $orderBy, $limit, $offset);
        return $queryBuilder;
    }
}