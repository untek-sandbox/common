<?php

namespace Untek\Database\Eloquent\Infrastructure\Abstract;

use Doctrine\DBAL\Exception;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Model\Query\Enums\OperatorEnum;
use Untek\Model\QueryFilter\Traits\QueryFilterTrait;

abstract class AbstractEloquentCrudRepository extends AbstractEloquentRepository implements
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
    public function findOneById(int $id, ?array $relations = null): object
    {
        $entity = $this->find($id, $relations);
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
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select('COUNT(*) as count');
        if ($criteria) {
            EloquentQueryBuilderHelper::setWhere($criteria, $queryBuilder);
        }
        return $queryBuilder->count();
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function deleteById(int $id): void
    {
        $entity = $this->findOneById($id);
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete($id);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function update(object $entity): void
    {
        $entity = $this->findOneById($entity->getId());
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->where('id', OperatorEnum::EQUAL, $entity->getId());
        $data = $this->dehydrate($entity);
        $queryBuilder->update($data);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function create(object $entity): void
    {
        $queryBuilder = $this->createQueryBuilder();
        $data = $this->dehydrate($entity);
        unset($data['id']);
        $lastId = $queryBuilder->insertGetId($data);
        if ($lastId) {
            $entity->setId($lastId);
        }
    }
}
