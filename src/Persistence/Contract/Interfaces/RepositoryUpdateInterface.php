<?php

namespace Untek\Persistence\Contract\Interfaces;

use Untek\Persistence\Contract\Exceptions\NotFoundException;

interface RepositoryUpdateInterface
{

    /**
     * Обновить сущность в хранилище.
     *
     * @param object $entity
     * @throws NotFoundException
     */
    public function update(object $entity): void;
}