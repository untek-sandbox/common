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

use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Model\Contract\Interfaces\RepositoryUpdateInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class <?= $className ?>

{

    public function __construct(
        private RepositoryFindOneByIdInterface|RepositoryUpdateInterface $repository
    )
    {
    }

    /**
     * @param \<?= $commandClassName ?> $command
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     */
    public function __invoke(\<?= $commandClassName ?> $command): void
    {
        $validator = new \<?= $validatorClassName ?>();
        $validator->validate($command);

        $entity = $this->repository->findOneById($command->getId());
        PropertyHelper::mergeObjects($command, $entity);
        $this->repository->update($entity);
    }
}