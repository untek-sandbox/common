<?php

namespace Untek\Model\Contract\Interfaces;

interface RepositoryCreateInterface
{

    /**
     * @param object $entity
     */
    public function create(object $entity): void;
}