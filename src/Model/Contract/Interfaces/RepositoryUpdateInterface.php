<?php

namespace Untek\Model\Contract\Interfaces;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;

interface RepositoryUpdateInterface
{

    /**
     * @param object $entity
     * @throws NotFoundException
     */
    public function update(object $entity): void;
}