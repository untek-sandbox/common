<?php

namespace Untek\Persistence\Contract\Interfaces;

interface RepositoryCreateInterface
{

    /**
     * Сохранить новую сущность в хранилище.
     *
     * @param object $entity
     */
    public function create(object $entity): void;
}