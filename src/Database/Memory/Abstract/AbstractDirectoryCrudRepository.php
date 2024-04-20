<?php

namespace Untek\Database\Memory\Abstract;

use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\FileSystem\Helpers\FindFileHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;

abstract class AbstractDirectoryCrudRepository extends AbstractMemoryRepository implements
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface
{

    protected array $collection = [];

    public function __construct(protected string $directory)
    {
    }

    protected function loadCollection(): void
    {
        if (empty($this->collection)) {
            $directory = $this->directory;
            $files = FindFileHelper::scanDir($directory);
            foreach ($files as $file) {
                $fileName = $directory . '/' . $file;
                $item = (new StoreFile($fileName))->load();
                $this->collection[] = $this->hydrate($item);
            }
        }
    }

    public function create(object $entity): void
    {
        $this->insert($entity);
    }

    protected function insert(object $entity)
    {
        $id = microtime(true);
        $id = $id * 10000;
        $id = intval($id);
        $entity->setId($id);
        $directory = $this->directory;
        $fileName = $directory . '/' . $id . '.json';
        $itemsRaw = $this->dehydrate($entity);
        $storeFile = new StoreFile($fileName);
        $storeFile->save($itemsRaw);
    }
    
    
    
    
    

    public function countBy(array $criteria): int
    {
        $collection = $this->findBy($criteria);
        return count($collection);
    }

    /**
     * @inheritdoc
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

    protected function getItems(): array
    {
        $this->loadCollection();
        return $this->collection;
    }
}
