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

use Untek\Model\Contract\Interfaces\RepositoryFindOneByIdInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Symfony\Contracts\Translation\TranslatorInterface;

class <?= $className ?>

{

    public function __construct(
        private TranslatorInterface $translator,
        private RepositoryFindOneByIdInterface $repository,
    )
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
        $validator = new \<?= $validatorClassName ?>($this->translator);
        $validator->validate($query);
        return $this->repository->findOneById($query->getId(), $query->getExpand());
    }
}