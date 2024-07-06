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
use Untek\Model\Contract\Interfaces\RepositoryCountByInterface;
use Untek\Model\DataProvider\DataProvider;
use Untek\Model\DataProvider\Dto\CollectionData;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Symfony\Contracts\Translation\TranslatorInterface;

class <?= $className ?>

{

    public function __construct(
        private TranslatorInterface $translator,
        private ObjectRepository|RepositoryCountByInterface $repository,
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $query
     * @return CollectionData
     * @throws UnprocessableEntityException
     */
    public function __invoke(\<?= $commandClassName ?> $query)
    {
        $validator = new \<?= $validatorClassName ?>($this->translator);
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
        return $dataProvider->findAll($query);
    }
}