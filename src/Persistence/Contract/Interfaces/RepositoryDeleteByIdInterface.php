<?php

namespace Untek\Persistence\Contract\Interfaces;

use Untek\Persistence\Contract\Exceptions\NotFoundException;

interface RepositoryDeleteByIdInterface
{

    /**
     * Удалить сущность из хранилища по ее ID.
     *
     * @param mixed $id
     * @throws NotFoundException
     */
    public function deleteById(mixed $id): void;
}