<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 * @var string $modelClass
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Contract\Interfaces\RepositoryCreateInterface;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class <?= $className ?>

{

    public function __construct(
        private RepositoryCreateInterface $repository
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $command
     * @return object
     * @throws UnprocessableEntityException
     */
    public function __invoke(\<?= $commandClassName ?> $command): object
    {
        $validator = new \<?= $validatorClassName ?>();
        $validator->validate($command);

        $entity = new \<?= $modelClass ?>();
        PropertyHelper::mergeObjects($command, $entity);
        $this->repository->create($entity);
        return $entity;
    }
}