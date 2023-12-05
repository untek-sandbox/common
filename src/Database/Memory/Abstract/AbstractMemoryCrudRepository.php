<?php

namespace Untek\Database\Memory\Abstract;

use Untek\Core\Instance\Helpers\PropertyHelper;
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

    protected array $collection = [];

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
        $lastId = $this->lastInsertId();
        $entity->setId($lastId + 1);
        $this->insert($entity);
    }

    protected function lastInsertId(): int {
        $collection = $this->findAll();
        $lastId = 0;
        foreach ($collection as $item) {
            if ($item->getId() > $lastId) {
                $lastId = $item->getId();
            }
        }
        return $lastId;
    }

    protected function insert(object $entity) {
        $this->loadCollection();
        $this->collection[] = $entity;
        $this->dumpCollection();
    }

    protected function dumpCollection(): void {

    }

    protected function loadCollection(): void {

    }

    protected function getItems(): array
    {
        $this->loadCollection();
        return $this->collection;
    }
}
