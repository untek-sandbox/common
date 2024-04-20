<?php

namespace Untek\Model\Contract\Interfaces;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;

interface RepositoryDeleteByIdInterface
{

    /**
     * @param int $id
     * @throws NotFoundException
     */
    public function deleteById(int $id): void;
}