<?php

namespace Untek\Database\Doctrine\Domain\Base;

use Doctrine\DBAL\Exception;
use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Database\Doctrine\Domain\Helpers\QueryBuilder\DoctrineQueryBuilderHelper;
use Untek\Model\Entity\Helpers\EntityHelper;

abstract class AbstractDoctrineCrudRepository extends AbstractDoctrineRepository implements
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface
{

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function findOneById(int $id): object
    {
        $entity = $this->find($id);
        if (empty($entity)) {
            throw new NotFoundException('Entity not found!');
        }
        return $entity;
    }

    /**
     * @inheritdoc
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function countBy(array $criteria): int
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('COUNT(*) as count');
        if ($criteria) {
            DoctrineQueryBuilderHelper::addWhere($criteria, $queryBuilder);
        }
        $statement = $this->getConnection()->executeQuery($queryBuilder->getSQL());
        return $statement->fetchOne();
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function deleteById(int $id): void
    {
        $entity = $this->findOneById($id);

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->getTableName());
        $queryBuilder->where($queryBuilder->expr()->eq('id', ':id'));
        $queryBuilder->setParameter('id', $entity->getId());
        $queryBuilder->execute();
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function update(object $entity): void
    {
        $entity = $this->findOneById($entity->getId());

        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->getTableName());

        $data = EntityHelper::toArrayForTablize($entity);
        unset($data['id']);
        foreach ($data as $column => $value) {
            $queryBuilder->set($column, ":$column");
            $queryBuilder->setParameter($column, $value);
        }

        $queryBuilder->where($queryBuilder->expr()->eq('id', ':id'));
        $queryBuilder->setParameter('id', $entity->getId());
        $queryBuilder->execute();
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function create(object $entity): void
    {
        $queryBuilder = $this->getQueryBuilder();
//        $data = EntityHelper::toArrayForTablize($entity);
        $data = $this->serializeEntity($entity);
        unset($data['id']);
        $columns = [];
        foreach ($data as $column => $value) {
            $columns[$column] = ":$column";
            $queryBuilder->setParameter($column, $value);
        }
        $status = $queryBuilder
            ->insert($this->getTableName())
            ->values($columns)
            ->execute();

        if ($status > 0) {
            $lastId = $this->getConnection()->lastInsertId();
            $entity->setId($lastId);
        }
    }
}