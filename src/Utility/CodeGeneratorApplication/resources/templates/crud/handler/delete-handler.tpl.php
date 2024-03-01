<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 */

?>

namespace <?= $namespace ?>;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Contract\Interfaces\RepositoryDeleteByIdInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class <?= $className ?>

{

    public function __construct(
        private RepositoryDeleteByIdInterface $repository
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $query
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     */
    public function __invoke(\<?= $commandClassName ?> $command): void
    {
        $validator = new \<?= $validatorClassName ?>();
        $validator->validate($command);

        $entity = $this->repository->findOneById($command->getId());
        $this->repository->deleteById($entity->getId());
    }
}