<?php

namespace Untek\Component\LogReader\Infrastructure\Persistence\JsonFile;

use Doctrine\Persistence\ObjectRepository;
use LimitIterator;
use SplFileObject;
use Untek\Component\LogReader\Domain\Model\LogItem;
use Untek\Component\LogReader\Infrastructure\Persistence\Normalizer\LogItemNormalizer;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Database\Base\Hydrator\DbNormalizerInterface;
use Untek\Database\Base\Hydrator\Traits\NormalizerTrait;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;

class LogRepository implements ObjectRepository, RepositoryCountByInterface
{

//    use EntityManagerAwareTrait;
    use NormalizerTrait;

    public function __construct(private string $path, private string $directory)
    {
    }

    protected function getNormalizer(): DbNormalizerInterface
    {
        return new LogItemNormalizer();
    }

    public function getClassName(): string
    {
        return LogItem::class;
    }

    private function getPath(array $criteria): string
    {
        $path = $this->path;
        if (isset($criteria['date'])) {
            $path = $this->directory . '/' . $criteria['date'] . '.log';
        }
        return $path;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
    {
        $path = $this->getPath($criteria);
        $file = new SplFileObject($path);
        $fileIterator = new LimitIterator($file);
        $collection = [];
        $offsetCount = 0;
        foreach ($fileIterator as $index => $line) {
            if (!empty($line)) {
                $item = json_decode($line, JSON_OBJECT_AS_ARRAY);
                $id = $index + 1;
                $item['id'] = $id;
                $isMatched = $this->isMatch($item, $criteria);
                if ($isMatched) {
                    if ($offset === null || $offsetCount >= $offset) {
                        $entity = $this->denormalize($item);
                        $collection[] = $entity;
                        if ($limit && count($collection) == $limit) {
                            break;
                        }
                    } else {
                        $offsetCount++;
                    }
                }
            }
        }
        return $collection;
    }

    public function countBy(array $criteria): int
    {
        $path = $this->getPath($criteria);
        $file = new SplFileObject($path);
        $fileIterator = new LimitIterator($file);
        $count = 0;
        foreach ($fileIterator as $index => $line) {
            if (!empty($line)) {
                $item = json_decode($line, JSON_OBJECT_AS_ARRAY);
                $isMatched = $this->isMatch($item, $criteria);
                if ($isMatched) {
                    $count++;
                }
            }
        }
        return $count;
    }

    private function isMatch(array $item, array $criteria): bool
    {
        if (isset($criteria['date'])) {
            unset($criteria['date']);
        }
        if ($criteria) {
            foreach ($criteria as $field => $value) {
                if ($field == 'message') {
                    if (!str_contains($item[$field], $value)) {
                        return false;
                    }
                } elseif ($item[$field] != $value) {
                    return false;
                }
            }
        }
        return true;
    }


    public function find($id)
    {
        $collection = $this->findBy(['id' => $id], [], 1, 0);
        if ($collection) {
            return ArrayHelper::first($collection);
        }
    }

    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }
}