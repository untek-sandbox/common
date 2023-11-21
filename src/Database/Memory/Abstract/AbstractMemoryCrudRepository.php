<?php

namespace Untek\Database\Memory\Abstract;

use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;

abstract class AbstractMemoryCrudRepository extends AbstractMemoryRepository implements
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface
{

    public function countBy(array $criteria): int
    {
        $collection = $this->findBy($criteria);
        return count($collection);
    }

    /**
     * @inheritdoc
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
     */
    public function deleteById(int $id): void
    {
        $entity = $this->findOneById($id);
    }

    /**
     * @inheritdoc
     */
    public function update(object $entity): void
    {
        $entity = $this->findOneById($entity->getId());
    }

    /**
     * @inheritdoc
     */
    public function create(object $entity): void
    {
        $collection = $this->findAll();
        $max = 0;
        foreach ($collection as $item) {
            if ($item->getId() > $max) {
                $max = $item->getId();
            }
        }
        $entity->setId($max + 1);
        $this->insert($entity);
    }

    protected function insert(object $entity) {

    }
}
