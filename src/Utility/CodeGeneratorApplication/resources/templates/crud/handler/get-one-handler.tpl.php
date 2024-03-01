<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $validatorClassName
 */

?>

namespace <?= $namespace ?>;

use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class <?= $className ?>

{

    public function __construct(private RepositoryFindOneByIdInterface $repository)
    {
    }

    /**
     * @param \<?= $commandClassName ?> $query
     * @return object
     * @throws UnprocessableEntityException
     * @throws NotFoundException
     */
    public function __invoke(\<?= $commandClassName ?> $query): object
    {
        $validator = new \<?= $validatorClassName ?>();
        $validator->validate($query);
        return $this->repository->findOneById($query->getId(), $query->getExpand());
    }
}