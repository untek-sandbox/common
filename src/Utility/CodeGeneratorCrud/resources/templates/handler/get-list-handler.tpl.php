<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 * @var string $modelName
 */

?>

namespace <?= $namespace ?>;

use Doctrine\Persistence\ObjectRepository;
use Forecast\Map\Example\Blog\Application\Queries\GetPostListQuery;
use Forecast\Map\Example\Blog\Application\Validators\GetPostListQueryValidator;
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\DataProvider\DataProvider;
use Untek\Model\DataProvider\Dto\CollectionData;
use Untek\Model\DataProvider\Exceptions\GreaterMaxPageException;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class <?= $className ?>

{

    public function __construct(private ObjectRepository|RepositoryCountByInterface $repository)
    {
    }

    /**
     * @param \<?= $commandClassName ?> $query
     * @return CollectionData
     * @throws UnprocessableEntityException
     */
    public function __invoke(\<?= $commandClassName ?> $query)
    {
        $validator = new \<?= $validatorClassName ?>();
        $validator->validate($query);
        return $this->findAll($query);
    }

    /**
     * @param object $query
     * @return CollectionData
     * @throws UnprocessableEntityException
     */
    protected function findAll(object $query): CollectionData
    {
        $dataProvider = new DataProvider($this->repository);
        try {
            return $dataProvider->findAll($query);
        } catch (GreaterMaxPageException $e) {
            UnprocessableEntityException::throwException($e->getMessage(), '[page][number]');
        }
    }
}